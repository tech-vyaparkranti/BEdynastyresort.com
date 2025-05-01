<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ServiceRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Service;
use App\Models\Category;

use App\Models\WebSiteElements;

class ServiceController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewService()
    {
        $category = Category::where("status",1)->where('tab_name','Services')->get();
        return view("Dashboard.Pages.manageService",compact("category"));
    }

    public function saveService(ServiceRequest $request)
    {
        Cache::forget("services");
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

    public function ImageUpload(ServiceRequest $request)
    {
        $maxId = Service::max(Service::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, 'image', "/images/service/", "facility_$maxId");
    }

    public function insertData(ServiceRequest $request)
    {
            $image_url = "";
            
            if($request->file('image')){
                $aboutImage = $this->ImageUpload($request);
                if($aboutImage['status'])
                {
                    $image_url = $aboutImage['data'];
                }
                else{
                    $image_url;
                }
            }           
            
            $createNewRow = new Service();
            $createNewRow->title = $request->title;
            $createNewRow->image = $image_url;
            $createNewRow->category = $request->category;
            $createNewRow->description = $request->description;
            $createNewRow->status = $request->status;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(ServiceRequest $request)
    {
            $updateModel = Service::find($request->id);
            $image_url = $updateModel->image;            
            if($request->file('image')){
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
            $updateModel->category = $request->category;
            $updateModel->description = $request->description;
            $updateModel->image = $image_url;
            $updateModel->status = 1;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(ServiceRequest $request)
    {
        $check = Service::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(ServiceRequest $request)
    {
        $check = Service::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function serviceData()
    {

        $query = Service::select(
            'title' ,'description' ,'image' ,'status','id','slug' ,'category'
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


    public function getService()
    {
        $services = Service::where('status', 1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'services' => $services,
        ];

        return response()->json($data, 200);
    }

    public function serviceDetails($id)
    {
        $singleService = Service::where('id',$id)->first();
        $data = [
            'status' => true,
            'success' => true,
            'singleService' => $singleService,
        ];

        return response()->json($data, 200);
    }
}
