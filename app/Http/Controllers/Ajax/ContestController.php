<?php

namespace App\Http\Controllers\Ajax;

use App\Models\ContestModel;
use App\Models\GroupModel;
use App\Models\ResponseModel;
use App\Models\AccountModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\ProcessSubmission;
use Auth;

class ContestController extends Controller
{
    public function fetchClarification(Request $request)
    {
        $request->validate([
            'cid' => 'required|integer',
        ]);

        $all_data=$request->all();

        $contestModel=new ContestModel();
        $clearance=$contestModel->judgeClearance($all_data["cid"], Auth::user()->id);
        if ($clearance<1) {
            return ResponseModel::err(2001);
        } else {
            return ResponseModel::success(200, null, $contestModel->fetchClarification($all_data["cid"]));
        }
    }

    public function updateProfessionalRate(Request $request)
    {
        if (Auth::user()->id!=1) {
            return ResponseModel::err(2001);
        }

        $request->validate([
            'cid' => 'required|integer'
        ]);

        $all_data=$request->all();

        $contestModel=new ContestModel();
        return $contestModel->updateProfessionalRate($all_data["cid"])?ResponseModel::success(200):ResponseModel::err(1001);
    }

    public function requestClarification(Request $request)
    {
        $request->validate([
            'cid' => 'required|integer',
            'title' => 'required|string|max:250',
            'content' => 'required|string|max:65536',
        ]);

        $all_data=$request->all();

        $contestModel=new ContestModel();
        $clearance=$contestModel->judgeClearance($all_data["cid"], Auth::user()->id);
        if ($clearance<2) {
            return ResponseModel::err(2001);
        } else {
            return ResponseModel::success(200, null, [
                "ccid" => $contestModel->requestClarification($all_data["cid"], $all_data["title"], $all_data["content"], Auth::user()->id)
            ]);
        }
    }

    public function rejudge(Request $request)
    {
        $request->validate([
            'cid' => 'required|integer'
        ]);

        $all_data=$request->all();
        if (Auth::user()->id!=1) {
            return ResponseModel::err(2001);
        }

        $contestModel=new ContestModel();
        $rejudgeQueue=$contestModel->getRejudgeQueue($all_data["cid"]);

        foreach ($rejudgeQueue as $r) {
            dispatch(new ProcessSubmission($r))->onQueue($r["oj"]);
        }

        return ResponseModel::success(200);
    }

    public function registContest(Request $request)
    {
        $request->validate([
            'cid' => 'required|integer'
        ]);

        $all_data=$request->all();

        $contestModel=new ContestModel();
        $groupModel=new GroupModel();
        $basic=$contestModel->basic($all_data["cid"]);

        if(!$basic["registration"]){
            return ResponseModel::err(4003);
        }
        if(strtotime($basic["registration_due"])<time()){
            return ResponseModel::err(4004);
        }
        if(!$basic["registant_type"]){
            return ResponseModel::err(4005);
        }
        if($basic["registant_type"]==1 && !$groupModel->isMember($basic["gid"], Auth::user()->id)){
            return ResponseModel::err(4005);
        }

        $ret=$contestModel->registContest($all_data["cid"], Auth::user()->id);

        return $ret ? ResponseModel::success(200) : ResponseModel::err(4006);
    }
}
