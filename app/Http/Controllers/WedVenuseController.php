<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WedVenuseRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\WedVenuse;
use App\Models\WebSiteElements;
use App\Models\Gallery;


class WedVenuseController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewWedVenuse()
    {
        
        return view("Dashboard.Pages.manageWedVenuse");
    }

    public function saveWedVenuse(WedVenuseRequest $request)
    {
        Cache::forget("wed_venuses");
        switch ($request->input("action")) {
            case "insert":
                $return = $this->insertData($request);
                break;
            case "update":
                $return = $this->updateData($request);
                break;
            case "enable":
                $return = $this->enableRow($request);
                break;
            case "disable":
                $return = $this->disableRow($request);
                break;
            default:
                $return = ["status" => false, "message" => "Unknown action.", "data" => null];
        }
        return response()->json($return);
    }

    public function ImageUpload(WedVenuseRequest $request)
    {
        $maxId = WedVenuse::max(WedVenuse::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, 'icon', "/images/facilities/", "facility_$maxId");
    }

    public function insertData(WedVenuseRequest $request)
    {
            $image_url = "";
            
            if($request->file('icon')){
                $aboutImage = $this->ImageUpload($request);
                if($aboutImage['status'])
                {
                    $image_url = $aboutImage['data'];
                }
                else{
                    $image_url;
                }
            }           
            
            $createNewRow = new WedVenuse();
            $createNewRow->title = $request->title;
            $createNewRow->icon = $image_url;
            $createNewRow->description = $request->description;
            $createNewRow->status = $request->status;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(WedVenuseRequest $request)
    {
            $updateModel = WedVenuse::find($request->id);
            $image_url = $updateModel->icon;            
            if($request->file('icon')){
                $aboutImage = $this->ImageUpload($request);
                if($aboutImage['status'])
                {
                    $image_url = $aboutImage['data'];
                }
                else{
                    $image_url;
                }
            }  
            $updateModel->title = $request->title;
            $updateModel->description = $request->description;
            $updateModel->icon = $image_url;
            $updateModel->status = 1;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(WedVenuseRequest $request)
    {
        $check = WedVenuse::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(WedVenuseRequest $request)
    {
        $check = WedVenuse::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function wedVenuseData()
    {

        $query = WedVenuse::select(
            'title' ,'description' ,'icon' ,'status','id'
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm mt-2">Edit</a>';

                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->id . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->id . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
                if ($row->status == 1) {
                    return $this->addDiv($btn_edit . $btn_disable);
                } else {
                    return $this->addDiv($btn_edit . $btn_enable);
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function allWedVenuse()
    {
        $allVenuses = WedVenuse::where('status',1)->get();
        $wedPhoto = Gallery::where("status",1)->where("category","wedding")->get();
        $data = [
            'status' => true,
            'success' => true,
            'allVenuses' => $allVenuses,
            'wedPhoto' => $wedPhoto,
        ];

        return response()->json($data, 200);
    }
    
}
