<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FaqRequest;
use App\Models\FAQ;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class FAQController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;
    
    public function viewFaq()
    {
        return view("Dashboard.Pages.manageFaq");
    }

    public function getFaq(){
        $query = FAQ::select(
           'questions','answers' ,'status','id'
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->id.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->id.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->status== 0){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })
            ->rawColumns(['action'])
            ->make(true);
        
        
    }

    public function saveFaq(FaqRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertFaq($request);
                    break;
                case "update":
                    $return = $this->updateFaq($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableFaq($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertFaq(FaqRequest $request){ 

            if (is_array($request->questions) && is_array($request->answers)) {
                foreach ($request->questions as $key => $question) {
                    FAQ::create([
                        'questions' => $question,
                        'answers' => $request->answers[$key] ?? null,
                    ]);
                }
            }
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        return $return;
    }
    
    public function updateFaq(FaqRequest $request){
        $faq = FAQ::find($request->id);
        if ($faq) {
                $faq->questions = $request->questions[0];
                $faq->answers = $request->answers[0];
                $faq->status = "1";
                $faq->save();              
                $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }


    public function enableDisableFaq(FaqRequest $request){
        $check = FAQ::find($request->id);
        if($check){
            if($request->input("action")=="enable"){
                $check->status = 1;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->status = 0;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function faqData()
    {
        $faqs = FAQ::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'faqs' => $faqs,
        ];
        return response()->json($data, 200);
    }
}
