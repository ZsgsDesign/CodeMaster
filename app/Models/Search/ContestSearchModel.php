<?php

namespace App\Models\Search;

use App\Models\ContestModel;
use Illuminate\Database\Eloquent\Model;
use Auth;

class ContestSearchModel extends Model
{
    protected $table='contest';
    protected $primaryKey='cid';

    private $rule=["Unknown", "ICPC", "OI", "Custom ICPC", "Custom OI"];

    public function search($key)
    {
        $result = [];
        //contest name find
        if(strlen($key) >= 2){
            $ret = self::where('name', 'like', $key.'%')
                ->select('cid', 'gid', 'name', 'rule', 'public', 'verified', 'practice', 'rated', 'anticheated', 'begin_time', 'end_time')
                ->get()->all();
            $user_id = Auth::user()->id;
            $contestModel = new ContestModel();
            foreach($ret as $c_index => $c){
                if(!$contestModel->judgeClearance($c['cid'],$user_id)){
                    unset($ret[$c_index]);
                }
            }
            foreach ($result as &$contest) {
                $contest["rule_parsed"]=$this->rule[$contest["rule"]];
                $contest["date_parsed"]=[
                    "date"=>date_format(date_create($contest["begin_time"]), 'j'),
                    "month_year"=>date_format(date_create($contest["begin_time"]), 'M, Y'),
                ];
                $contest["length"]=$contestModel->calcLength($contest["begin_time"], $contest["end_time"]);
            }
            unset($contest);

            if(!empty($ret)){
                $result += $ret;
            }
        }
        return $result;
    }
}