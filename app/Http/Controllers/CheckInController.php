<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Traits\CommonFunctions;
use App\Traits\ResponseAPI;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class CheckInController extends Controller
{
    use CommonFunctions;
    use ResponseAPI;
    
    public function store(Request $request)
    {
        $checkIn = CheckIn::create([
            "checkIn"=> $request->checkIn,
            "checkOut" => $request->checkOut ,
            'name' => $request->name,
            "phone_no" => $request->phone_no ,
            'ip_address' => $request->ip(),
        ]);
        return response()->json([
            'status' => true,
            'success' => true,
        ], 200);
    }

    public function checkInPage(){
        return view("Dashboard.Pages.checkInManagement");
    }

    public function checkInDataTable(){
        
        $query = CheckIn::select(
    DB::raw('DATE_FORMAT(CONVERT_TZ(checkIn, "+00:00", "+05:30"), "%W %M %e %Y %r") as checkIn_formatted'),
    DB::raw('DATE_FORMAT(CONVERT_TZ(checkOut, "+00:00", "+05:30"), "%W %M %e %Y %r") as checkOut_formatted'),
    'name', 'phone_no', 'ip_address', 'id',
    DB::raw('DATE_FORMAT(CONVERT_TZ(created_at, "+00:00", "+05:30"), "%W %M %e %Y %r") as created_at_formatted')
);

return DataTables::of($query)
    ->addIndexColumn()
    ->make(true);

    }
}
