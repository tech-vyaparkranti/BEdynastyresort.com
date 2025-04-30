<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GuestExperienceRequest;
use App\Models\GuestExperience;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class GuestExperienceController extends Controller
{
    use CommonFunctions;

    public function viewGuestExp()
    {
        return view("Dashboard.Pages.manageExpVideo");
    }

    public function saveGuestExp(GuestExperienceRequest $request)
    {
        Cache::forget("guest_experiences");
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

    public function ImageUpload(GuestExperienceRequest $request)
    {
        $maxId = GuestExperience::max(GuestExperience::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, "image", "/images/gallery/", "gallery_$maxId");
    }

    public function insertData(GuestExperienceRequest $request)
    {         
            $createNewRow = new GuestExperience();
            $createNewRow->status = $request->status;
            $createNewRow->category = $request->category;
            $createNewRow->video_link = $request->video_link;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(GuestExperienceRequest $request)
    {
            $updateModel = GuestExperience::find($request->id);           
            $updateModel->status = $request->status;
            $updateModel->category = $request->category;
            $updateModel->video_link = $request->video_link;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(GuestExperienceRequest $request)
    {
        $check = GuestExperience::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(GuestExperienceRequest $request)
    {
        $check = GuestExperience::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function guestExpData()
    {

        $query = GuestExperience::select(
           'video_link','status','id','category'
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


    public function getGuestExp()
    {
        $rooms = GuestExperience::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'rooms' => $rooms,
        ];
        return response()->json($data, 200);
    }
}
