<?php

namespace App\Http\Controllers;

use App\Http\Requests\OurGuestRequest;
use App\Models\OurGuestModel;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OurGuestController extends Controller
{
    use CommonFunctions;
    //

    public function guestreviewSlider(){
        return view("Dashboard.Pages.guest_review");
    }

    public function guestreviewData(){
        $query = OurGuestModel::select(
        OurGuestModel::ID,
        // OurGuestModel::HEADING_TOP,
        // OurGuestModel::HEADING_BOTTOM,
        OurGuestModel::HEADING_MIDDLE,
        OurGuestModel::SLIDE_SORTING,
        OurGuestModel::SLIDE_STATUS);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{OurGuestModel::ID}.')" class="btn btn-danger btn-sm">Disable Slide</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{OurGuestModel::ID}.')" class="btn btn-primary btn-sm">Enable Slide</a>';
                if($row->{OurGuestModel::SLIDE_STATUS}==OurGuestModel::SLIDE_STATUS_DISABLED){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function guestreviewSaveSlide(OurGuestRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertSlide($request);
                    break;
                case "update":
                    $return = $this->updateSlide($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableSlide($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertSlide(OurGuestRequest $request){
        // $imageUpload = $this->slideImageUpload($request);
        $OurGuestModel = new OurGuestModel();
            // $OurGuestModel->{OurGuestModel::IMAGE} = $imageUpload["data"];
            // $OurGuestModel->{OurGuestModel::HEADING_TOP} = $request->input(OurGuestModel::HEADING_TOP);
            $OurGuestModel->{OurGuestModel::HEADING_MIDDLE} = $request->input(OurGuestModel::HEADING_MIDDLE);
            // $OurGuestModel->{OurGuestModel::HEADING_BOTTOM} = $request->input(OurGuestModel::HEADING_BOTTOM);
            $OurGuestModel->{OurGuestModel::SLIDE_STATUS} = $request->input(OurGuestModel::SLIDE_STATUS);
            $OurGuestModel->{OurGuestModel::SLIDE_SORTING} = $request->input(OurGuestModel::SLIDE_SORTING);           
            $OurGuestModel->{OurGuestModel::STATUS} = 1;
            $OurGuestModel->{OurGuestModel::CREATED_BY} = Auth::user()->id;
            $OurGuestModel->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        // if($imageUpload["status"]){
        //     $OurGuestModel = new OurGuestModel();
        //     // $OurGuestModel->{OurGuestModel::IMAGE} = $imageUpload["data"];
        //     $OurGuestModel->{OurGuestModel::HEADING_TOP} = $request->input(OurGuestModel::HEADING_TOP);
        //     $OurGuestModel->{OurGuestModel::HEADING_MIDDLE} = $request->input(OurGuestModel::HEADING_MIDDLE);
        //     $OurGuestModel->{OurGuestModel::HEADING_BOTTOM} = $request->input(OurGuestModel::HEADING_BOTTOM);
        //     $OurGuestModel->{OurGuestModel::SLIDE_STATUS} = $request->input(OurGuestModel::SLIDE_STATUS);
        //     $OurGuestModel->{OurGuestModel::SLIDE_SORTING} = $request->input(OurGuestModel::SLIDE_SORTING);           
        //     $OurGuestModel->{OurGuestModel::STATUS} = 1;
        //     $OurGuestModel->{OurGuestModel::CREATED_BY} = Auth::user()->id;
        //     $OurGuestModel->save();
        //     $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
        //     $this->forgetSlides();
        // }else{
        //     $return = $imageUpload;
        // }
        return $return;
    }

    // public function slideImageUpload(OurGuestRequest $request){
    //     $maxId = OurGuestModel::max(OurGuestModel::ID);
    //     $maxId += 1;
    //     $timeNow = strtotime($this->timeNow());
    //     $maxId .= "_$timeNow";
    //     return $this->uploadLocalFile($request,"image","/website/uploads/Slider/","slide_$maxId");
    // }

    public function updateSlide(OurGuestRequest $request){
        $check = OurGuestModel::where([OurGuestModel::ID=>$request->input(OurGuestModel::ID),OurGuestModel::STATUS=>1])->first();
        if($check){
            // if($request->input(OurGuestModel::IMAGE)){
            //     $imageUpload =$this->slideImageUpload($request);
            //     if($imageUpload["status"]){
            //         $check->{OurGuestModel::IMAGE} = $imageUpload["data"];
            //         $check->{OurGuestModel::SLIDE_SORTING} = $request->input(OurGuestModel::SLIDE_SORTING);
            //         $check->{OurGuestModel::HEADING_TOP} = $request->input(OurGuestModel::HEADING_TOP);
            //         $check->{OurGuestModel::HEADING_MIDDLE} = $request->input(OurGuestModel::HEADING_MIDDLE);
            //         $check->{OurGuestModel::HEADING_BOTTOM} = $request->input(OurGuestModel::HEADING_BOTTOM);
            //         $check->{OurGuestModel::SLIDE_STATUS} = $request->input(OurGuestModel::SLIDE_STATUS);
            //         $check->{OurGuestModel::UPDATED_BY} = Auth::user()->id;
            //         $check->save();
            //         $this->forgetSlides();
            //         $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];
            //     }else{
            //         $return = $imageUpload;
            //     }
                $check->{OurGuestModel::SLIDE_SORTING} = $request->input(OurGuestModel::SLIDE_SORTING);
                // $check->{OurGuestModel::HEADING_TOP} = $request->input(OurGuestModel::HEADING_TOP);
                $check->{OurGuestModel::HEADING_MIDDLE} = $request->input(OurGuestModel::HEADING_MIDDLE);
                // $check->{OurGuestModel::HEADING_BOTTOM} = $request->input(OurGuestModel::HEADING_BOTTOM);
                $check->{OurGuestModel::SLIDE_STATUS} = $request->input(OurGuestModel::SLIDE_STATUS);                    $check->{OurGuestModel::UPDATED_BY} = Auth::user()->id;
                $check->save();
                $this->forgetSlides();
                $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];
            // }else{
            //     $check->{OurGuestModel::SLIDE_SORTING} = $request->input(OurGuestModel::SLIDE_SORTING);
            //     $check->{OurGuestModel::HEADING_TOP} = $request->input(OurGuestModel::HEADING_TOP);
            //     $check->{OurGuestModel::HEADING_MIDDLE} = $request->input(OurGuestModel::HEADING_MIDDLE);
            //     $check->{OurGuestModel::HEADING_BOTTOM} = $request->input(OurGuestModel::HEADING_BOTTOM);
            //     $check->{OurGuestModel::SLIDE_STATUS} = $request->input(OurGuestModel::SLIDE_STATUS);
            //     $check->{OurGuestModel::UPDATED_BY} = Auth::user()->id;
            //     $check->save();
            //     $this->forgetSlides();
            //     $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            // }
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    // public function enableDisableSlide(OurGuestRequest $request){
    //     $check = OurGuestModel::find($request->input(OurGuestModel::ID));
    //     if($check){
    //         $check->{OurGuestModel::UPDATED_BY} = Auth::user()->id;
    //         if($request->input("action")=="enable"){
    //             $check->{OurGuestModel::SLIDE_STATUS} = OurGuestModel::SLIDE_STATUS_LIVE;
    //             $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
    //         }else{
    //             $check->{OurGuestModel::SLIDE_STATUS} = OurGuestModel::SLIDE_STATUS_DISABLED;
    //             $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
    //         }
    //         $this->forgetSlides();
    //         $check->save();
    //     }else{
    //         $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
    //     }
    //     return $return;
    // }
    public function enableDisableSlide(OurGuestRequest $request) {
        $check = OurGuestModel::find($request->input(OurGuestModel::ID));
        
        if ($check) {
            $check->{OurGuestModel::UPDATED_BY} = Auth::user()->id;
            
            if ($request->input("action") == "enable") {
                $check->{OurGuestModel::SLIDE_STATUS} = OurGuestModel::SLIDE_STATUS_LIVE;
                $return = ["status" => 1, "message" => "Enabled successfully.", "data" => ""];
            } else {
                $check->{OurGuestModel::SLIDE_STATUS} = OurGuestModel::SLIDE_STATUS_DISABLED;
                $return = ["status" => 1, "message" => "Disabled successfully.", "data" => ""];
            }
            
            $this->forgetSlides();
            $check->save();
        } else {
            $return = ["status" => 0, "message" => "Details not found.", "data" => ""];
        }
        
        return $return;
    }
    
}
