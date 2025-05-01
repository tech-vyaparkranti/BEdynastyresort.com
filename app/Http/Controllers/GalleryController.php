<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GalleryRequest;
use App\Models\Gallery;
use App\Models\Category;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    use CommonFunctions;

    public function viewGallery()
    {
        $category = Category::where("status",1)->where('tab_name','Gallery')->get();
        return view("Dashboard.Pages.manageGallery",compact('category'));
    }

    public function saveGallery(GalleryRequest $request)
    {
        Cache::forget("galleries");
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

    public function ImageUpload(GalleryRequest $request)
    {
        $maxId = Gallery::max(Gallery::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, "image", "/images/gallery/", "gallery_$maxId");
    }

    public function insertData(GalleryRequest $request)
    {
            $image_url = "";
            $bannerImage = $this->ImageUpload($request);
            if ($bannerImage["status"]) {
                $image_url = $bannerImage["data"];
            } else {
                return $bannerImage;
            }           
            
            $createNewRow = new Gallery();
            $createNewRow->image = $image_url;
            $createNewRow->details = $request->details;
            $createNewRow->category = $request->category;
            $createNewRow->title = $request->title;
            $createNewRow->status = $request->status;
            $createNewRow->video_link = $request->video_link;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(GalleryRequest $request)
    {
            $updateModel = Gallery::find($request->input(Gallery::ID));
            $image_url = $updateModel->image;
            if($request->hasFile('image')){
                $bannerImage = $this->ImageUpload($request);
                if ($bannerImage["status"]) {
                    $image_url = $bannerImage["data"];
                } else {
                   $image_url;
                }
            }  
            $updateModel->image = $image_url;
            $updateModel->details = $request->details;
            $updateModel->category = $request->category;
            $updateModel->title = $request->title;
            $updateModel->status = $request->status;
            $updateModel->video_link = $request->video_link;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(GalleryRequest $request)
    {
        $check = Gallery::where(Gallery::ID, $request->input(Gallery::ID))->first();
        if ($check) {
            $check->status = 1;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(GalleryRequest $request)
    {
        $check = Gallery::where(Gallery::ID, $request->input(Gallery::ID))->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function galleryData()
    {

        $query = Gallery::select(
           'title' ,'image','video_link','details','status','category','id'
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm mt-2">Edit</a>';

                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->{Gallery::ID} . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->{Gallery::ID} . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
                if ($row->status == 1) {
                    return $this->addDiv($btn_edit . $btn_disable);
                } else {
                    return $this->addDiv($btn_edit . $btn_enable);
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function getGallery()
    {
        $rooms = Gallery::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'rooms' => $rooms,
        ];
        return response()->json($data, 200);
    }
}
