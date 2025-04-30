<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TestimonialRequest;
use App\Models\Testimonial;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    use CommonFunctions;

    public function viewTestimonial(){
        return view("Dashboard.Pages.manageTestimonial");
    }

    public function getTestimonial(){
            $query = Testimonial::select(
                Testimonial::IMAGE,
                Testimonial::ID,
                Testimonial::DESCRIPTION,
                Testimonial::DESIGNATION,
                Testimonial::REVIEW,
                Testimonial::SORTING,
                Testimonial::NAME,
                Testimonial::STATUS
            );
            return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Testimonial::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Testimonial::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Testimonial::STATUS}== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        
        
    }

    public function saveTestimonial(TestimonialRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertTestimonial($request);
                    break;
                case "update":
                    $return = $this->updateTestimonial($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableTestimonial($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertTestimonial(TestimonialRequest $request){ 
        $imageUpload = $this->heroImageUpload($request);
        
        if($imageUpload['status'])
        {
            $Testimonial = new Testimonial();
            $Testimonial->{Testimonial::IMAGE} = $imageUpload['data'];
            $Testimonial->{Testimonial::STATUS} = $request->input(Testimonial::STATUS);           
            $Testimonial->{Testimonial::DESCRIPTION} = $request->input(Testimonial::DESCRIPTION);
            $Testimonial->{Testimonial::DESIGNATION} = $request->input(Testimonial::DESIGNATION);
            $Testimonial->{Testimonial::REVIEW} = $request->input(Testimonial::REVIEW);
            $Testimonial->{Testimonial::SORTING} = $request->input(Testimonial::SORTING);
            $Testimonial->{Testimonial::NAME} = $request->input(Testimonial::NAME);
            $Testimonial->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function heroImageUpload(TestimonialRequest $request){
        $maxId = Testimonial::max(Testimonial::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/testimonial/","slide_$maxId");
    }

    
    public function updateTestimonial(TestimonialRequest $request){
        $check = Testimonial::where([Testimonial::ID=>$request->input(Testimonial::ID)])->first();

        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->heroImageUpload($request);
                
                if($imageUpload["status"]){
                    $check->{Testimonial::IMAGE} = $imageUpload["data"];                                                         
                }
            }
            $check->{Testimonial::DESCRIPTION} = $request->input(Testimonial::DESCRIPTION);
            $check->{Testimonial::DESIGNATION} = $request->input(Testimonial::DESIGNATION);
            $check->{Testimonial::REVIEW} = $request->input(Testimonial::REVIEW);
            $check->{Testimonial::SORTING} = $request->input(Testimonial::SORTING);
            $check->{Testimonial::NAME} = $request->input(Testimonial::NAME);
            $check->{Testimonial::STATUS} = $request->input(Testimonial::STATUS);
            $check->save();
            $this->forgetSlides();
            $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableTestimonial(TestimonialRequest $request){
        $check = Testimonial::find($request->input(Testimonial::ID));
        if($check){
            if($request->input("action")=="enable"){
                $check->{Testimonial::STATUS} = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Testimonial::STATUS} = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function testimonialData()
    {
        $testimonials = Testimonial::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'Testimonials' => $testimonials,
        ];

        return response()->json($data, 200);
    }

    public function TestimonialDetails($id)
    {
        $testimonial = Testimonial::where('id',$id)->first();
        if($testimonial)
        {
            $data = [
                'status' => true,
                'success' => true,
                'testimonial' => $testimonial,
            ];
    
        }
        else{
            $data = [
                'status' => false,
                'success' => false,
                'message' => "details not found for given id",
            ];
        }
        
        return response()->json($data, 200);
    }
}
