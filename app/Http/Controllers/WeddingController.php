<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WeddingRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Wedding;

class WeddingController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewWedding()
    {
        
        return view("Dashboard.Pages.manageWedding");
    }

    public function saveWedding(WeddingRequest $request)
    {
        Cache::forget("weddings");
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

    public function multipleImage(WeddingRequest $request)
    {
        $maxId = Wedding::max(Wedding::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadMultipleLocalFiles($request, 'images', "/images/wedding/", "service_image_$maxId");
    }

    public function insertData(WeddingRequest $request)
    {
            $image_url = "";         
            if($request->file('images')){
                $aboutImage = $this->multipleImage($request);
                $image_url = array_column($aboutImage, 'data');
            }           
            
            $createNewRow = new Wedding();
            $createNewRow->title = $request->title;
            $createNewRow->images = json_encode($image_url);
            $createNewRow->description = $request->description;
            $createNewRow->short_desc = $request->short_desc;
            $createNewRow->status = $request->status;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(WeddingRequest $request)
    {        
            $updateModel = Wedding::find($request->id);
            $image_url = $updateModel->images;
            if($request->file('images')){
                $aboutImage = $this->multipleImage($request);
                $image_url = array_column($aboutImage, 'data');
                $updateModel->images = json_encode($image_url);
            }  
            $updateModel->title = $request->title;
            $updateModel->description = $request->description;
            $updateModel->short_desc = $request->short_desc;
            $updateModel->status = 1;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(WeddingRequest $request)
    {
        $check = Wedding::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(WeddingRequest $request)
    {
        $check = Wedding::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function weddingData()
    {
        $query = Wedding::select(
            'title' ,'description' ,'images' ,'status','id','short_desc'
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


    public function getWedding()
    {
        $wedding = Wedding::where('status', 1)->orderBy('updated_at', 'desc')->first();
        $data = [
            'status' => true,
            'success' => true,
            'wedding' => $wedding,
        ];

        return response()->json($data, 200);
    }
}
