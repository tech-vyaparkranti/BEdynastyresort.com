<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RoomRequest;
use App\Models\Room;
use App\Models\Category;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;                                                                                            

class RoomController extends Controller
{
    use CommonFunctions;

    public function viewRoom()
    {
        $category = Category::where("status",1)->where('tab_name','Room')->get();
        return view("Dashboard.Pages.manageRooms",compact('category'));
    }

    public function saveRoom(RoomRequest $request)
    {
        Cache::forget("rooms");
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

    public function ImageUpload(RoomRequest $request)
    {
        $maxId = Room::max(Room::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, "banner_image", "/images/room_banner/", "room_$maxId");
    }
    public function multipleImage(RoomRequest $request)
    {
        $maxId = Room::max(Room::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadMultipleLocalFiles($request, "images", "/images/room_images/", "room_$maxId");
    }

    public function insertData(RoomRequest $request)
    {
        
            $image_url = "";
            $bannerImage = $this->ImageUpload($request);
            $Images = $this->multipleImage($request);
            $ImageUrls = array_column($Images, 'data');

            if ($bannerImage["status"]) {
                $banner_url = $bannerImage["data"];
            } else {
                return $bannerImage;
            }           
            
            $createNewRow = new Room();
            $createNewRow->banner_image = $banner_url;
            $createNewRow->images = json_encode($ImageUrls);
            $createNewRow->details = $request->details;
            $createNewRow->category = $request->category;
            $createNewRow->title = $request->title;
            $createNewRow->size = $request->size;
            $createNewRow->status = 1;
            $createNewRow->person_allow = $request->person_allow;
            $createNewRow->video_link = $request->video_link;
            $createNewRow->amenities = $request->amenities;
            $createNewRow->features = $request->features;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(RoomRequest $request)
    {

            $updateModel = Room::find($request->input(Room::ID));
            $banner_url = $updateModel->{Room::BANNER_IMAGE};
            if($request->file(Room::BANNER_IMAGE)){
                $bannerImage = $this->ImageUpload($request);
                if ($bannerImage["status"]) {
                    $banner_url = $bannerImage["data"];
                } else {
                    return $banner_url;
                }
            }  

            $ImageUrls = $updateModel->images;
            if($request->hasFile('images')){
                $Images = $this->multipleImage($request);
                $urls = array_column($Images, 'data');
                $ImageUrls = json_encode($urls);
            }  
            
            $updateModel->banner_image = $banner_url;
            $updateModel->images = $ImageUrls;
            $updateModel->details = $request->details;
            $updateModel->category = $request->category;
            $updateModel->title = $request->title;
            $updateModel->size = $request->size;
            $updateModel->person_allow = $request->person_allow;
            $updateModel->video_link = $request->video_link;
            $updateModel->amenities = $request->amenities;
            $updateModel->features = $request->features;
            $updateModel->status = 1;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(RoomRequest $request)
    {
        $check = Room::where(Room::ID, $request->input(Room::ID))->first();
        if ($check) {
            $check->{Room::STATUS} = 1;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(RoomRequest $request)
    {
        $check = Room::where(Room::ID, $request->input(Room::ID))->first();
        if ($check) {
            $check->{Room::STATUS} = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function roomData()
    {

        $query = Room::select(
            Room::TITLE,
            Room::ROOM_DETAILS,
            Room::BANNER_IMAGE,
            Room::STATUS,
            Room::ID,
            Room::ROOM_IMAGE,
            Room::SIZE,
            Room::CATEGORY,
            Room::PERSON_ALLOW,
            Room::FEATURES,
            Room::AMENITIES,
            Room::VIDEO_LINK,
        );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm mt-2">Edit</a>';

                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->{Room::ID} . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->{Room::ID} . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
                if ($row->{Room::STATUS} == 1) {
                    return $this->addDiv($btn_edit . $btn_disable);
                } else {
                    return $this->addDiv($btn_edit . $btn_enable);
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function getRoom()
    {
        $rooms = Room::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'rooms' => $rooms,
        ];
        return response()->json($data, 200);
    }

    public function roomDetail($slug)
    {
        $singleRoom = Room::where('slug',$slug)->first();
        $data = [
            'status' => true,
            'success' => true,
            'singleRoom' => $singleRoom,
        ];

        return response()->json($data, 200);
    }
}
