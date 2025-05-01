<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\WedTimelineRequest;
use Yajra\DataTables\Facades\DataTables;
use App\Models\WedTimeline;
use App\Models\WebSiteElements;

class WedTimelineController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewWedTimeline()
    {
        
        return view("Dashboard.Pages.manageWedTimeline");
    }

    public function saveWedTimeline(WedTimelineRequest $request)
    {
        Cache::forget("wed_timelines");
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

    public function insertData(WedTimelineRequest $request)
    {           
            $createNewRow = new WedTimeline();
            $createNewRow->title = $request->title;
            $createNewRow->description = $request->description;
            $createNewRow->status = $request->status;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(WedTimelineRequest $request)
    {
            $updateModel = WedTimeline::find($request->id);
            $updateModel->title = $request->title;
            $updateModel->description = $request->description;
            $updateModel->status = 1;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(WedTimelineRequest $request)
    {
        $check = WedTimeline::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(WedTimelineRequest $request)
    {
        $check = WedTimeline::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function wedTimelineData()
    {

        $query = WedTimeline::select(
            'title' ,'description' ,'status','id'
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

    public function allWedTimeline()
    {
        $wedTimeline = WedTimeline::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'wedTimeline' => $wedTimeline,
        ];
        return response()->json($data, 200);
    }

    
}
