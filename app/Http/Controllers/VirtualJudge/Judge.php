<?php
namespace App\Http\Controllers\VirtualJudge;

use App\Models\SubmissionModel;
use App\Models\JudgerModel;
use App\Models\ContestModel;
use App\Http\Controllers\VirtualJudge\Core;
use Requests;
use Exception;

class Judge extends Core
{
    private $MODEL;
    public $ret=[];
    public function __construct()
    {
        $this->MODEL=new SubmissionModel();
        $ret=[];

        $uva_v=[
            'Wrong answer'=>"Wrong Answer",
            'Accepted'=>"Accepted",
            'Runtime error'=>"Runtime Error",
            'Time limit exceeded'=>"Time Limit Exceed",
            'Presentation error'=>"Presentation Error",
            'Submission error'=>'Submission Error',
            'Compilation error'=>"Compile Error",
            'Output Limit Exceeded'=>"Output Limit Exceeded",
        ];

        $codeforces_v=[
            "COMPILATION_ERROR"=>"Compile Error",
            "RUNTIME_ERROR"=> "Runtime Error",
            "WRONG_ANSWER"=> "Wrong Answer",
            "TIME_LIMIT_EXCEEDED"=>"Time Limit Exceed",
            "OK"=>"Accepted",
            "MEMORY_LIMIT_EXCEEDED"=>"Memory Limit Exceed",
            "PRESENTATION_ERROR"=>"Presentation Error"];

        $spoj_v=[
            "compilation error"=>"Compile Error",
            "runtime error"=> "Runtime Error",
            "wrong answer"=> "Wrong Answer",
            "time limit exceeded"=>"Time Limit Exceed",
            "accepted"=>"Accepted"
        ];

        $contesthunter_v=[
            '正确'=>"Accepted",
            '答案错误'=>"Wrong Answer",
            '超出时间限制'=>"Time Limit Exceed",
            '运行时错误'=>"Runtime Error",
            "超出内存限制"=>"Memory Limit Exceed",
            '比较器错误'=>'Submission Error',
            '超出输出限制'=>"Output Limit Exceeded",
            '编译错误'=>"Compile Error",
        ];

        $poj_v=[
            'Accepted'=>"Accepted",
            "Presentation Error"=>"Presentation Error",
            'Time Limit Exceeded'=>"Time Limit Exceed",
            "Memory Limit Exceeded"=>"Memory Limit Exceed",
            'Wrong Answer'=>"Wrong Answer",
            'Runtime Error'=>"Runtime Error",
            'Output Limit Exceeded'=>"Output Limit Exceeded",
            'Compile Error'=>"Compile Error",
        ];

        $vijos_v=[
            'Accepted'=>"Accepted",
            'Wrong Answer'=>"Wrong Answer",
            'Time Exceeded'=>"Time Limit Exceed",
            "Memory Exceeded"=>"Memory Limit Exceed",
            'Runtime Error'=>"Runtime Error",
            'Compile Error'=>"Compile Error",
            'System Error'=>"Submission Error",
            'Canceled'=>"Submission Error",
            'Unknown Error'=>"Submission Error",
            'Ignored'=>"Submission Error",
        ];

        $pta_v=[
            'ACCEPTED'=>"Accepted",
            'COMPILE_ERROR'=>"Compile Error",
            'FLOAT_POINT_EXCEPTION'=>"Runtime Error",
            'INTERNAL_ERROR'=>"Submission Error",
            "MEMORY_LIMIT_EXCEEDED"=>"Memory Limit Exceed",
            'MULTIPLE_ERROR'=>"Runtime Error",
            'NON_ZERO_EXIT_CODE'=>"Runtime Error",
            'NO_ANSWER'=>"Compile Error",
            'OUTPUT_LIMIT_EXCEEDED'=>"Output Limit Exceeded",
            'OVERRIDDEN'=>"Submission Error",
            'PARTIAL_ACCEPTED'=>"Partially Accepted",
            "PRESENTATION_ERROR"=>"Presentation Error",
            'RUNTIME_ERROR'=>"Runtime Error",
            'SAMPLE_ERROR'=>"Wrong Answer",
            'SEGMENTATION_FAULT'=>"Runtime Error",
            'SKIPPED'=>"Submission Error",
            'TIME_LIMIT_EXCEEDED'=>"Time Limit Exceed",
            'WRONG_ANSWER'=>"Wrong Answer",
        ];

        $result=$this->MODEL->get_waiting_submission();
        $judger=new JudgerModel();
        $contestModel=new ContestModel();
        $curl=new Curl();

        $cf=$this->get_last_codeforces($this->MODEL->count_waiting_submission(2));
        $poj=[];

        $pojJudgerList=$judger->list(4);
        $pojJudgerName=urlencode($pojJudgerList[0]["handle"]);
        if ($this->MODEL->count_waiting_submission(5)) {
            $this->appendPOJStatus($poj, $pojJudgerName);
        }
        // $uva=$this->get_last_uva($this->MODEL->count_waiting_submission('Uva'));
        // $uval=$this->get_last_uvalive($this->MODEL->count_waiting_submission('UvaLive'));
        // $sj=$this->get_last_spoj($this->MODEL->count_waiting_submission('Spoj'));

        $i=0;
        $j=0;
        $k=0;
        $l=0;
        foreach ($result as $row) {
            if ($row['oid']==2) {
                if (isset($codeforces_v[$cf[$i][2]])) {
                    $sub['verdict']=$codeforces_v[$cf[$i][2]];
                    $sub["score"]=$sub['verdict']=="Accepted" ? 1 : 0;
                    $sub['time']=$cf[$i][0];
                    $sub['memory']=$cf[$i][1];
                    $sub['remote_id']=$cf[$i][3];

                    $ret[$row['sid']]=[
                        "verdict"=>$sub['verdict']
                    ];

                    $this->MODEL->update_submission($row['sid'], $sub);
                }
                $i++;
            } elseif ($row['oid']==3) {
                try {
                    $res=Requests::get('http://contest-hunter.org:83/record/'.$row['remote_id']);
                    preg_match('/<dt>状态<\/dt>[\s\S]*?<dd class=".*?">(.*?)<\/dd>/m', $res->body, $match);
                    $status=$match[1];
                    if (!array_key_exists($status, $contesthunter_v)) {
                        continue;
                    }
                    $sub['verdict']=$contesthunter_v[$status];
                    $sub["score"]=$sub['verdict']=="Accepted" ? 1 : 0;
                    $sub['remote_id']=$row['remote_id'];
                    if ($sub['verdict']!="Submission Error" && $sub['verdict']!="Compile Error") {
                        preg_match('/占用内存[\s\S]*?(\d+).*?KiB/m', $res->body, $match);
                        $sub['memory']=$match[1];
                        $maxtime=0;
                        preg_match_all('/<span class="pull-right muted">(\d+) ms \/ \d+ KiB<\/span>/', $res->body, $matches);
                        foreach ($matches[1] as $time) {
                            if ($time<$maxtime) {
                                $maxtime=$time;
                            }
                        }
                        $sub['time']=$maxtime;
                    } else {
                        $sub['memory']=0;
                        $sub['time']=0;
                    }

                    $ret[$row['sid']]=[
                        "verdict"=>$sub['verdict']
                    ];
                    $this->MODEL->update_submission($row['sid'], $sub);
                } catch (Exception $e) {
                }
            } elseif ($row['oid']==4) {
                if (!isset($poj[$row['remote_id']])) {
                    $this->appendPOJStatus($poj, $pojJudgerName, $row['remote_id']);
                    if (!isset($poj[$row['remote_id']])) {
                        continue;
                    }
                }
                $status=$poj[$row['remote_id']];
                $sub['verdict']=$poj_v[$status['verdict']];
                $sub["score"]=$sub['verdict']=="Accepted" ? 1 : 0;
                $sub['time']=$status['time'];
                $sub['memory']=$status['memory'];
                $sub['remote_id']=$row['remote_id'];

                $ret[$row['sid']]=[
                    "verdict"=>$sub['verdict']
                ];
                $this->MODEL->update_submission($row['sid'], $sub);
            } elseif ($row['oid']==5) {
                try {
                    $res=Requests::get('https://vijos.org/records/'.$row['remote_id']);
                    preg_match('/<span class="record-status--text \w*">\s*(.*?)\s*<\/span>/', $res->body, $match);
                    $status=$match[1];
                    if (!array_key_exists($status, $vijos_v)) {
                        continue;
                    }
                    $sub['verdict']=$vijos_v[$status];
                    preg_match('/<dt>分数<\/dt>\s*<dd>(\d+)<\/dd>/', $res->body, $match);
                    $isOI=$row['cid'] && $contestModel->rule($row['cid'])==2;
                    if ($isOI) {
                        $sub['score']=$match[1];
                        if ($sub['verdict']=="Wrong Answer" && $sub['score']!=0) {
                            $sub['verdict']='Partially Accepted';
                        }
                    } else {
                        $sub['score']=$match[1]==100 ? 100 : 0;
                    }
                    $sub['remote_id']=$row['remote_id'];
                    if ($sub['verdict']!="Submission Error" && $sub['verdict']!="Compile Error") {
                        $maxtime=0;
                        preg_match_all('/<td class="col--time">(?:&ge;)?(\d+)ms<\/td>/', $res->body, $matches);
                        foreach ($matches as $match) {
                            if ($match[1]>$maxtime) {
                                $maxtime=$match[1];
                            }
                        }
                        $sub['time']=$maxtime;
                        preg_match('/<dt>峰值内存<\/dt>\s*<dd>(?:&ge;)?([\d.]+) ([KM])iB<\/dd>/', $res->body, $match);
                        $memory=$match[1];
                        if ($match[2]=='M') {
                            $memory*=1024;
                        }
                        $sub['memory']=intval($memory);
                    } else {
                        $sub['memory']=0;
                        $sub['time']=0;
                    }

                    $ret[$row['sid']]=[
                        "verdict"=>$sub['verdict']
                    ];
                    $this->MODEL->update_submission($row['sid'], $sub);
                } catch (Exception $e) {
                }
            } elseif ($row['oid']==6) {
                try {
                    $remoteId=explode('|', $row['remote_id']);
                    $response=$curl->grab_page("https://pintia.cn/api/problem-sets/$remoteId[0]/submissions/".$remoteId[1], 'pta');
                    $data=json_decode($response, true);
                    if (!isset($pta_v[$data['submission']['status']])) {
                        continue;
                    }
                    $sub['verdict']=$pta_v[$data['submission']['status']];
                    $isOI=$row['cid'] && $contestModel->rule($row['cid'])==2;
                    $sub['score']=$data['submission']['score'];
                    if (!$isOI) {
                        if ($sub['verdict']=="Partially Accepted") {
                            $sub['verdict']='Wrong Answer';
                            $sub['score']=0;
                        }
                    }
                    $sub['remote_id']=$row['remote_id'];
                    $sub['memory']=$data['submission']['memory'] / 1024;
                    $sub['time']=$data['submission']['time'] * 1000;

                    $ret[$row['sid']]=[
                        "verdict"=>$sub['verdict']
                    ];
                    $this->MODEL->update_submission($row['sid'], $sub);
                } catch (Exception $e) {
                }
            }
            // if ($row['oid']=='Spoj') {
            //     if (isset($spoj_v[$sj[$j][2]])) {
            //         $sub['verdict']=$spoj_v[$sj[$j][2]];
            //         $sub['time']=$sj[$j][0];
            //         $sub['memory']=$sj[$j][1];
            //         $v=$sub['verdict'];
            //         $ret[$row['sid']]="<div style='color:{$color[$v]};'>"  .$sub['Verdict']. "</div>";
            //         $this->MODEL->update_submission($row['sid'], $sub);
            //     }
            //     $j++;
            // }
            // if ($row['oid']=='Uva') {
            //     if (isset($uva_v[$uva[$k][2]])) {
            //         $sub['verdict']=$uva_v[$uva[$k][2]];
            //         $sub['time']=$uva[$k][0];
            //         $sub['memory']=$uva[$k][1];
            //         $v=$sub['verdict'];
            //         $ret[$row['sid']]="<div style='color:{$color[$v]};'>"  .$sub['Verdict']. "</div>";
            //         $this->MODEL->update_submission($row['sid'], $sub);
            //     }
            //     $k++;
            // }
            // if ($row['oid']=='UvaLive') {
            //     if (isset($uva_v[$uval[$l][2]])) {
            //         $sub['verdict']=$uva_v[$uval[$l][2]];
            //         $sub['time']=$uval[$l][0];
            //         $sub['memory']=$uval[$l][1];
            //         $v=$sub['verdict'];
            //         $ret[$row['sid']]="<div style='color:{$color[$v]};'>"  .$sub['Verdict']. "</div>";
            //         $this->MODEL->update_submission($row['sid'], $sub);
            //     }
            //     $l++;
            // }
        }
        $this->ret=$ret;
    }
    /**
     * [Not Finished] Get last time UVa submission by using API.
     *
     * @param integer $num
     *
     * @return void
     */
    private function get_last_uva($num)
    {
        $ret=array();
        if ($num==0) {
            return $ret;
        }
        $response=$this->grab_page('https://uva.onlinejudge.org', 'uva');
        if (!(strpos($response, 'UVa Online Judge - Offline')!==false)) {
            $this->uva_live_login('https://uva.onlinejudge.org', 'https://uva.onlinejudge.org/index.php?option=com_comprofiler&task=login', 'uva');
        } else {
            return $ret;
        }

        $i=0;
        while (true) {
            $response=$this->grab_page("https://uva.onlinejudge.org/index.php?option=com_onlinejudge&Itemid=9&limit=50&limitstart={$i}", 'uva');

            $exploded=explode('<table cellpadding="4" cellspacing="0" border="0" width="100%">', $response);
            $table=explode('</table>', $exploded[1])[0];

            $table=explode('<tr class="sectiontableentry', $table);

            for ($j=1; $j<count($table); $j++) {
                $num--;
                $sub=$table[$j];

                $sub=explode('<td>', $sub);
                $verdict=explode('</td>', $sub[3])[0];
                $time=explode('</td>', $sub[5])[0];

                if ((strpos($verdict, '<a href=')!==false)) {
                    $verdict=explode('</a', explode('>', explode('<a href=', $verdict)[1])[1])[0];
                }

                array_push($ret, array($time * 1000, -1, $verdict));

                if ($num==0) {
                    return array_reverse($ret);
                }
            }
            $i+=50;
        }
    }

    /**
     * [Not Finished] Get last time UVa Live submission by using API.
     *
     * @param integer $num
     *
     * @return void
     */
    private function get_last_uvalive($num)
    {
        $ret=array();
        if ($num==0) {
            return $ret;
        }

        $this->uva_live_login('https://icpcarchive.ecs.baylor.edu', 'https://icpcarchive.ecs.baylor.edu/index.php?option=com_comprofiler&task=login', 'uvalive');

        $i=0;
        while (true) {
            $response=$this->grab_page("https://icpcarchive.ecs.baylor.edu/index.php?option=com_onlinejudge&Itemid=9&limit=50&limitstart={$i}", 'uvalive');

            $exploded=explode('<table cellpadding="4" cellspacing="0" border="0" width="100%">', $response);
            $table=explode('</table>', $exploded[1])[0];

            $table=explode('<tr class="sectiontableentry', $table);

            for ($j=1; $j<count($table); $j++) {
                $num--;
                $sub=$table[$j];

                $sub=explode('<td>', $sub);
                $verdict=explode('</td>', $sub[3])[0];
                $time=explode('</td>', $sub[5])[0];

                if ((strpos($verdict, '<a href=')!==false)) {
                    $verdict=explode('</a', explode('>', explode('<a href=', $verdict)[1])[1])[0];
                }

                array_push($ret, array($time * 1000, -1, $verdict));

                if ($num==0) {
                    return array_reverse($ret);
                }
            }
            $i+=50;
        }
    }


    /**
     * [Not Finished] Get last time CodeForces submission by using API.
     *
     * @param integer $num
     *
     * @return void
     */
    private function get_last_codeforces($num)
    {
        $ret=array();
        if ($num==0) {
            return $ret;
        }

        $ch=curl_init();
        $url="http://codeforces.com/api/user.status?handle=codemaster4&from=1&count={$num}";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response=curl_exec($ch);
        curl_close($ch);
        $result=json_decode($response, true);
        if ($result["status"]=="OK") {
            for ($i=0; $i<$num; $i++) {
                if (!isset($result["result"][$i]["verdict"])) {
                    return array_reverse($ret);
                }
                array_push($ret, array($result["result"][$i]["timeConsumedMillis"], $result["result"][$i]["memoryConsumedBytes"] / 1000, $result["result"][$i]["verdict"], $result["result"][$i]["id"]));
            }
        }
        return array_reverse($ret);
    }

    /**
     * [Not Finished] Get last time SPOJ submission by using API.
     *
     * @param integer $num
     *
     * @return void
     */
    private function get_last_spoj($num)
    {
        $ret=array();
        if ($num==0) {
            return $ret;
        }

        $i=0;
        while (true) {
            $response=file_get_contents("http://www.spoj.com/status/our_judge/all/start={$i}");


            $exploded=explode('<table class="problems table newstatus">', $response);
            $table=explode('</table>', $exploded[1])[0];

            $table=explode('<td class="statustext text-center">', $table);

            for ($j=1; $j<count($table); $j++) {
                $num--;
                $sub=$table[$j];


                $verdict=explode('</td>', explode('manual="0">', explode('<td class="statusres text-center"', $sub)[1])[1])[0];
                if ((strpos($verdict, '<strong>')!==false)) {
                    $verdict=explode('</strong>', explode('<strong>', $verdict)[1])[0];
                }

                if ((strpos($verdict, '(')!==false)) {
                    $verdict=explode('(', $verdict)[0];
                }
                if (is_numeric(trim($verdict))) {
                    $verdict='accepted';
                }

                $time=explode('</a>', explode('title="See the best solutions">', $sub)[1])[0];
                $time=trim($time);
                if ($time=='-') {
                    $time=0;
                }

                $memory=explode('</td', explode('>', explode('<td class="smemory statustext text-center"', $sub)[1])[1])[0];
                $memory=trim($memory);
                if ($memory=='-') {
                    $memory=0;
                } else {
                    $memory=substr($memory, 0, strlen($memory)-1);
                }

                array_push($ret, array($time * 1000, $memory * 1000, trim($verdict)));
                if ($num==0) {
                    return array_reverse($ret);
                }
            }
            $i+=20;
        }
    }

    private function appendPOJStatus(&$results, $judger, $first=null)
    {
        if ($first!==null) {
            $first++;
        }
        $res=Requests::get("http://poj.org/status?user_id={$judger}&top={$first}");
        $rows=preg_match_all('/<tr align=center><td>(\d+)<\/td><td>.*?<\/td><td>.*?<\/td><td>.*?<font color=.*?>(.*?)<\/font>.*?<\/td><td>(\d*)K?<\/td><td>(\d*)(?:MS)?<\/td>/', $res->body, $matches);
        for ($i=0; $i<$rows; $i++) {
            $results[$matches[1][$i]]=[
                'verdict'=>$matches[2][$i],
                'memory'=>$matches[3][$i] ? $matches[3][$i] : 0,
                'time'=>$matches[4][$i] ? $matches[4][$i] : 0,
            ];
        }
    }
}
