<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AwardRequest;
use App\Models\Awards;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class AwardsController extends Controller
{
    use CommonFunctions;

    public function viewAward(){
        return view("Dashboard.Pages.manageAward");
    }

    public function getAward(){
            $query = Awards::select(
                Awards::IMAGE,
                Awards::ID,
                Awards::POSITION,
                Awards::STATUS
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Awards::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Awards::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Awards::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function saveAward(AwardRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertAward($request);
                    break;
                case "update":
                    $return = $this->updateAward($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableAward($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertAward(AwardRequest $request){ 
        $imageUpload = $this->heroImageUpload($request);
        
        if($imageUpload['status'])
        {
            $awards = new Awards();
            $awards->{Awards::IMAGE} = $imageUpload['data'];
            $awards->{Awards::STATUS} = $request->input(Awards::STATUS);           
            $awards->{Awards::POSITION} = $request->input(Awards::POSITION);
            $awards->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function heroImageUpload(AwardRequest $request){
        $maxId = Awards::max(Awards::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/awards/","slide_$maxId");
    }

    
    public function updateAward(AwardRequest $request){
        $check = Awards::where([Awards::ID=>$request->input(Awards::ID)])->first();

        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->heroImageUpload($request);
                
                if($imageUpload["status"]){
                    $check->{Awards::IMAGE} = $imageUpload["data"];                                                         
                }
            }
            $check->{Awards::POSITION} = $request->input(Awards::POSITION);
            $check->{Awards::STATUS} = $request->input(Awards::STATUS);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableAward(AwardRequest $request){
        $check = Awards::find($request->input(Awards::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{Awards::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Awards::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function awardsData()
    {
        $awards = Awards::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'awards' => $awards,
        ];

        return response()->json($data, 200);
    }

}
