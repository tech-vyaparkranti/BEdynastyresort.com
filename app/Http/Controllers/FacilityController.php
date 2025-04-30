<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FacilityRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Facility;
use App\Models\WebSiteElements;


class FacilityController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewFacility()
    {
        
        return view("Dashboard.Pages.manageFacility");
    }

    public function saveFacility(FacilityRequest $request)
    {
        Cache::forget("facilities");
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

    public function ImageUpload(FacilityRequest $request)
    {
        $maxId = Facility::max(Facility::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, 'icon', "/images/facilities/", "facility_$maxId");
    }

    public function insertData(FacilityRequest $request)
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
            
            $createNewRow = new Facility();
            $createNewRow->title = $request->title;
            $createNewRow->icon = $image_url;
            $createNewRow->description = $request->description;
            $createNewRow->status = $request->status;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(FacilityRequest $request)
    {
            $updateModel = Facility::find($request->id);
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

    public function enableRow(FacilityRequest $request)
    {
        $check = Facility::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(FacilityRequest $request)
    {
        $check = Facility::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function facilityData()
    {

        $query = Facility::select(
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


    public function getFacility()
    {
        $facility = Facility::where('status', 1)->paginate(4);
        $elements = WebSiteElements::where('status','1')->where('element','facility_banner')->value('element_details');

        $data = [
            'status' => true,
            'success' => true,
            'facility_banner' => $elements,
            'facility' => $facility,
        ];

        return response()->json($data, 200);
    }

    public function facilityDetails($id)
    {
        $facility = Facility::where('id',$id)->first();
        $data = [
            'status' => true,
            'success' => true,
            'facility' => $facility,
        ];

        return response()->json($data, 200);
    }
}
