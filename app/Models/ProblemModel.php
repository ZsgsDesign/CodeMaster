<?php

namespace App\Models;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProblemModel extends Model
{
    protected $tableName='problem';

    public function detail($pcode, $cid=null)
    {
        $prob_detail=DB::table($this->tableName)->where("pcode", $pcode)->first();
        // [Depreciated] Joint Query was depreciated here for code maintenance reasons
        if (!is_null($prob_detail)) {
            if ($prob_detail["markdown"]) {
                $prob_detail["parsed"]=[
                    "description"=>clean(Markdown::convertToHtml($prob_detail["description"])),
                    "input"=>clean(Markdown::convertToHtml($prob_detail["input"])),
                    "output"=>clean(Markdown::convertToHtml($prob_detail["output"])),
                    "note"=>clean(Markdown::convertToHtml($prob_detail["note"]))
                ];
            } else {
                $prob_detail["parsed"]=[
                    "description"=>$prob_detail["description"],
                    "input"=>$prob_detail["input"],
                    "output"=>$prob_detail["output"],
                    "note"=>$prob_detail["note"]
                ];
            }
            $prob_detail["update_date"]=date_format(date_create($prob_detail["update_date"]), 'm/d/Y H:i:s');
            $prob_detail["oj_detail"]=DB::table("oj")->where("oid", $prob_detail["OJ"])->first();
            $prob_detail["samples"]=DB::table("problem_sample")->where("pid", $prob_detail["pid"])->get()->all();
            $prob_detail["tags"]=DB::table("problem_tag")->where("pid", $prob_detail["pid"])->get()->all();
            if ($cid) {
                $frozen_time=strtotime(DB::table("contest")->where(["cid"=>$cid])->select("end_time")->first()["end_time"]);
                $prob_stat=DB::table("submission")->select(
                    DB::raw("count(sid) as submission_count"),
                    DB::raw("sum(verdict='accepted') as passed_count"),
                    DB::raw("sum(verdict='accepted')/count(sid)*100 as ac_rate")
                )->where([
                    "pid"=>$prob_detail["pid"],
                    "cid"=>$cid,
                ])->where("submission_date", "<", $frozen_time)->first();
                $prob_detail["points"]=DB::table("contest_problem")->where(["cid"=>$cid])->select("points")->first()["points"];
            } else {
                $prob_stat=DB::table("submission")->select(
                    DB::raw("count(sid) as submission_count"),
                    DB::raw("sum(verdict='accepted') as passed_count"),
                    DB::raw("sum(verdict='accepted')/count(sid)*100 as ac_rate")
                )->where(["pid"=>$prob_detail["pid"]])->first();
                $prob_detail["points"]=0;
            }
            if ($prob_stat["submission_count"]==0) {
                $prob_detail["submission_count"]=0;
                $prob_detail["passed_count"]=0;
                $prob_detail["ac_rate"]=0;
            } else {
                $prob_detail["submission_count"]=$prob_stat["submission_count"];
                $prob_detail["passed_count"]=$prob_stat["passed_count"];
                $prob_detail["ac_rate"]=round($prob_stat["ac_rate"], 2);
            }
        }
        return $prob_detail;
    }

    public function basic($pid)
    {
        return DB::table($this->tableName)->where("pid", $pid)->first();
    }

    public function tags()
    {
        return DB::table("problem_tag")->groupBy('tag')->select("tag", DB::raw('count(*) as tag_count'))->orderBy('tag_count', 'desc')->limit(12)->get()->all();
    }

    public function ojs()
    {
        return DB::table("oj")->orderBy('oid', 'asc')->limit(12)->get()->all();
    }

    public function isBlocked($pid, $cid=null)
    {
        $conflictContests=DB::table("contest")
                            ->join("contest_problem", "contest.cid", "=", "contest_problem.cid")
                            ->where("end_time", ">", date("Y-m-d H:i:s"))
                            ->where(["verified"=>1, "pid"=>$pid])
                            ->select(["contest_problem.cid as cid"])
                            ->get()
                            ->all();
        if (empty($conflictContests)) {
            return false;
        }
        foreach ($conflictContests as $c) {
            if ($cid==$c["cid"]) {
                return false;
            }
        }
        header("HTTP/1.1 403 Forbidden");
        exit();
        return true;
    }

    public function list($filter)
    {
        // $prob_list = DB::table($this->tableName)->select("pid","pcode","title")->get()->all(); // return a array
        $preQuery=DB::table($this->tableName);
        if ($filter['oj']) {
            $preQuery=$preQuery->where(["OJ"=>$filter['oj']]);
        }
        if ($filter['tag']) {
            $preQuery=$preQuery->join("problem_tag", "problem.pid", "=", "problem_tag.pid")->where(["tag"=>$filter['tag']]);
        }
        $prob=json_decode($preQuery->select("problem.pid as pid", "pcode", "title")->paginate(20)->toJSON(), true);
        if (empty($prob["data"])) {
            return null;
        }
        $cur_page=$prob["current_page"];
        $tot_page=$prob["last_page"];
        $temp_page_list=[];
        if ($tot_page<=5) {
            for ($i=1; $i<=$tot_page; $i++) {
                array_push($temp_page_list, $i);
            }
        } else {
            for ($i=$cur_page-2; $i<=$cur_page+2; $i++) {
                array_push($temp_page_list, $i);
            }
            if ($temp_page_list[0]<1) {
                $temp_page_list[0]=$temp_page_list[4]+1;
            }
            if ($temp_page_list[1]<1) {
                $temp_page_list[1]=$temp_page_list[4]+2;
            }
            if ($temp_page_list[3]>$tot_page) {
                $temp_page_list[3]=$temp_page_list[0]-1;
            }
            if ($temp_page_list[4]>$tot_page) {
                $temp_page_list[4]=$temp_page_list[0]-2;
            }
            sort($temp_page_list);
        }
        $prob["paginate"]["data"]=[];
        $prob["paginate"]["previous"]=is_null($prob["prev_page_url"]) ? "" : "?page=".($cur_page-1).($filter["oj"] ? "&oj={$filter['oj']}" : "").($filter["tag"] ? "&tag={$filter['tag']}" : "");
        $prob["paginate"]["next"]=is_null($prob["next_page_url"]) ? "" : "?page=".($cur_page+1).($filter["oj"] ? "&oj={$filter['oj']}" : "").($filter["tag"] ? "&tag={$filter['tag']}" : "");
        foreach ($temp_page_list as $p) {
            $url="?page=$p";
            if ($filter["oj"]) {
                $url.="&oj={$filter['oj']}";
            }
            if ($filter["tag"]) {
                $url.="&tag={$filter['tag']}";
            }
            array_push($prob["paginate"]["data"], [
                "page"=>$p,
                "cur"=> $p==$cur_page ? 1 : 0,
                "url"=>$url
            ]);
        }
        foreach ($prob["data"] as &$p) {
            $prob_stat=DB::table("submission")->select(
                DB::raw("count(sid) as submission_count"),
                DB::raw("sum(verdict='accepted') as passed_count"),
                DB::raw("sum(verdict='accepted')/count(sid)*100 as ac_rate")
            )->where(["pid"=>$p["pid"]])->first();
            if ($prob_stat["submission_count"]==0) {
                $p["submission_count"]=0;
                $p["passed_count"]=0;
                $p["ac_rate"]=0;
            } else {
                $p["submission_count"]=$prob_stat["submission_count"];
                $p["passed_count"]=$prob_stat["passed_count"];
                $p["ac_rate"]=round($prob_stat["ac_rate"], 2);
            }
        }
        return $prob;
    }

    public function existPCode($pcode)
    {
        $temp=DB::table($this->tableName)->where(["pcode"=>$pcode])->select("pcode")->first();
        return empty($temp) ? null : $temp["pcode"];
    }

    public function pid($pcode)
    {
        $temp=DB::table($this->tableName)->where(["pcode"=>$pcode])->select("pid")->first();
        return empty($temp) ? 0 : $temp["pid"];
    }

    public function pcode($pid)
    {
        $temp=DB::table($this->tableName)->where(["pid"=>$pid])->select("pcode")->first();
        return empty($temp) ? 0 : $temp["pcode"];
    }

    public function clearTags($pid)
    {
        DB::table("problem_tag")->where(["pid"=>$pid])->delete();
        return true;
    }

    public function addTags($pid, $tag)
    {
        DB::table("problem_tag")->insert(["pid"=>$pid, "tag"=>$tag]);
        return true;
    }

    public function getSolvedCount($oid)
    {
        return DB::table($this->tableName)->select("pid", "solved_count")->where(["OJ"=>$oid])->get()->all();
    }

    public function updateDifficulty($pid, $diff_level)
    {
        DB::table("problem_tag")->where(["pid"=>$pid])->update(["difficulty"=>$diff_level]);
        return true;
    }

    public function insertProblem($data)
    {
        $pid=DB::table($this->tableName)->insertGetId([
            'difficulty'=>-1,
            'file'=>$data['file'],
            'title'=>$data['title'],
            'time_limit'=>$data['time_limit'],
            'memory_limit'=>$data['memory_limit'],
            'OJ'=>$data['OJ'],
            'description'=>$data['description'],
            'input'=>$data['input'],
            'output'=>$data['output'],
            'note'=>$data['note'],
            'input_type'=>$data['input_type'],
            'output_type'=>$data['output_type'],
            'pcode'=>$data['pcode'],
            'contest_id'=>$data['contest_id'],
            'index_id'=>$data['index_id'],
            'origin'=>$data['origin'],
            'source'=>$data['source'],
            'solved_count'=>$data['solved_count'],
            'update_date'=>date("Y-m-d H:i:s"),
            'tot_score'=>$data['tot_score'],
            'partial'=>$data['partial'],
            'markdown'=>$data['markdown'],
            'special_compiler'=>$data['special_compiler'],
        ]);

        if (!empty($data["sample"])) {
            foreach ($data["sample"] as $d) {
                DB::table("problem_sample")->insert([
                    'pid'=>$pid,
                    'sample_input'=>$d['sample_input'],
                    'sample_output'=>$d['sample_output'],
                ]);
            }
        }

        return $pid;
    }

    public function updateProblem($data)
    {
        DB::table($this->tableName)->where(["pcode"=>$data['pcode']])->update([
            'difficulty'=>-1,
            'file'=>$data['file'],
            'title'=>$data['title'],
            'time_limit'=>$data['time_limit'],
            'memory_limit'=>$data['memory_limit'],
            'OJ'=>$data['OJ'],
            'description'=>$data['description'],
            'input'=>$data['input'],
            'output'=>$data['output'],
            'note'=>$data['note'],
            'input_type'=>$data['input_type'],
            'output_type'=>$data['output_type'],
            'contest_id'=>$data['contest_id'],
            'index_id'=>$data['index_id'],
            'origin'=>$data['origin'],
            'source'=>$data['source'],
            'solved_count'=>$data['solved_count'],
            'update_date'=>date("Y-m-d H:i:s"),
            'tot_score'=>$data['tot_score'],
            'partial'=>$data['partial'],
            'markdown'=>$data['markdown'],
            'special_compiler'=>$data['special_compiler'],
        ]);

        $pid=$this->pid($data['pcode']);

        DB::table("problem_sample")->where(["pid"=>$pid])->delete();

        if (!empty($data["sample"])) {
            foreach ($data["sample"] as $d) {
                DB::table("problem_sample")->insert([
                    'pid'=>$pid,
                    'sample_input'=>$d['sample_input'],
                    'sample_output'=>$d['sample_output'],
                ]);
            }
        }

        return $pid;
    }
}
