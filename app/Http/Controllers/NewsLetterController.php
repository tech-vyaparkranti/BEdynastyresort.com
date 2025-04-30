<?php

namespace App\Http\Controllers;

use App\Models\NewsLetterEmail;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class NewsLetterController extends Controller
{
    use CommonFunctions;
    public function subscribeNewsLetter(Request $request){
        try{
            $validate = Validator::make($request->all(),
            [
                NewsLetterEmail::EMAIL_ID=>"required|unique:news_letter_emails",
                "captcha"=>"required|captcha"
            ],
            [
                "captcha.required"=>"Captcha text is required.",
                "captcha.captcha"=>"Captcha text is not correct."
            ]);
            if($validate->fails()){
                $response = $this->returnMessage($validate->getMessageBag()->first());
            }else{
                $object = new NewsLetterEmail();
                $object->{NewsLetterEmail::EMAIL_ID} = $request->{NewsLetterEmail::EMAIL_ID};
                $object->{NewsLetterEmail::IP_ADDRESS} = $this->getIp();
                $object->{NewsLetterEmail::USER_AGENT} = $request->userAgent();
                $object->save();
                $response = $this->returnMessage("Thank you subscribed successfully.",true);
            }
        }catch(Exception $exception){
            report($exception);
            $response = $this->returnMessage("Something went wrong.");
        }
        return response()->json($response);
    }

    public function manageNewsLetterAdmin(){
        return view("Dashboard.Pages.manageNewsLetterAdmin");
    }

    public function getNewsLetterData(){
        
        $query = NewsLetterEmail::select(
            NewsLetterEmail::ID,
            NewsLetterEmail::EMAIL_ID,
            NewsLetterEmail::CREATED_AT,
            NewsLetterEmail::IP_ADDRESS,
            NewsLetterEmail::USER_AGENT
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->{NewsLetterEmail::CREATED_AT})->toDayDateTimeString();
            })
            ->make(true);
    }
}
