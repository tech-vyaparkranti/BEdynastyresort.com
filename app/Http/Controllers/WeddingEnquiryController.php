<?php

namespace App\Http\Controllers;

use App\Models\WeddingEnquiry;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class WeddingEnquiryController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;

    public function store(Request $request)
    {
        try{
            WeddingEnquiry::create([
                "your_name" => $request->your_name ,
                'partner_name' => $request->partner_name ,
                'email' => $request->email ,
                'phone' => $request->phone ,
                'guest_count' => $request->guest_count ,
                'wed_date' => $request->wed_date,
                'add_detail' => $request->add_detail,
            ]);
            $response = $this->success("Thank you for your message. We will contact you shortly.",[]);

        }catch(Exception $exception){
            report($exception);
            $response = $this->error("Something went wrong. " . $exception->getMessage());
        }
        return $response;
    }

    public function wedEnquiry(){
        return view("Dashboard.Pages.wedEnquiry");
    }

    public function enquiryDataTable(){
        
        $query = WeddingEnquiry::select(
            "your_name" ,'partner_name' ,'email' ,'phone' ,'guest_count' ,'wed_date','add_detail','id',
            DB::raw('DATE_FORMAT(CONVERT_TZ('.'created_at'.',"+00:00","+05:30"), "%W %M %e %Y %r") as created_at_formatted')
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
}
