<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PackagesRequest;
use App\Models\Packages;
use App\Models\Category;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables; 

class PackagesController extends Controller
{
    use CommonFunctions;

    public function viewPackages()
    {
        $category = Category::where("status",1)->where('tab_name','Packages')->get();
        return view("Dashboard.Pages.managePackage",compact('category'));
    }

    public function savePackages(PackagesRequest $request)
    {
        Cache::forget("packages");
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

    public function ImageUpload(PackagesRequest $request)
    {
        $maxId = Packages::max(Packages::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, "image", "/images/package/", "room_$maxId");
    }
    // public function multipleImage(PackagesRequest $request)
    // {
    //     $maxId = Packages::max(Packages::ID);
    //     $maxId += 1;
    //     $timeNow = strtotime($this->timeNow());
    //     $maxId .= "_$timeNow";
    //     return $this->uploadMultipleLocalFiles($request, "images", "/images/room_images/", "room_$maxId");
    // }

    public function insertData(PackagesRequest $request)
    {
            $bannerImage = $this->ImageUpload($request);
            $image_url = "";
            if ($bannerImage["status"]) {
                $image_url = $bannerImage["data"];
            } else {
                return $bannerImage;
            }           
            
            $createNewRow = new Packages();
            $createNewRow->image = $image_url;
            $createNewRow->price = $request->price;
            $createNewRow->category = $request->category;
            $createNewRow->title = $request->title;
            $createNewRow->offer_price = $request->offer_price;
            $createNewRow->allowance_details = $request->allowance_details;
            $createNewRow->short_desc = $request->short_desc;
            $createNewRow->description = $request->description;
            $createNewRow->features = $request->features;
            $createNewRow->save();

            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(PackagesRequest $request)
    {
            $updateModel = Packages::find($request->input(Packages::ID));
            $image_url = $updateModel->image;
            if($request->file('image')){
                $bannerImage = $this->ImageUpload($request);
                if ($bannerImage["status"]) {
                    $image_url = $bannerImage["data"];
                } else {
                    $image_url;
                }
            }  
            
            $updateModel->image = $image_url;
            $updateModel->price = $request->price;
            $updateModel->category = $request->category;
            $updateModel->title = $request->title;
            $updateModel->offer_price = $request->offer_price;
            $updateModel->allowance_details = $request->allowance_details;
            $updateModel->short_desc = $request->short_desc;
            $updateModel->description = $request->description;
            $updateModel->features = $request->features;
            $updateModel->status = 1;
            $updateModel->save();
            $return = $this->returnMessage("Update successfully.", true);
        
        return $return;
    }

    public function enableRow(PackagesRequest $request)
    {
        $check = Packages::where(Packages::ID, $request->input(Packages::ID))->first();
        if ($check) {
            $check->status = 1;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(PackagesRequest $request)
    {
        $check = Packages::where(Packages::ID, $request->input(Packages::ID))->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function packageData()
    {
        $query = Packages::select(
            "title" ,'price','offer_price','allowance_details','short_desc' ,'features' ,'description' ,'status',"image" ,"category",'id'
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


    public function getPackages()
    {
        $packages = Packages::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'packages' => $packages,
        ];
        return response()->json($packages, 200);
    }

    public function packagesDetail($id)
    {
        $singlePackages = Packages::where('id',$id)->first();
        $data = [
            'status' => true,
            'success' => true,
            'singlePackages' => $singlePackages,
        ];

        return response()->json($data, 200);
    }
}
