<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\TeamRequest;
use App\Models\TeamInfo;

class TeamInfoController extends Controller
{
    use CommonFunctions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTeam()
    {    
        return view("Dashboard.Pages.manageTeam");
    }

    public function saveTeamInfo(TeamRequest $request)
    {
        Cache::forget("team_infos");
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

    public function ImageUpload(TeamRequest $request)
    {
        $maxId = TeamInfo::max(TeamInfo::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, 'image', "/images/team/", "team_$maxId");
    }

    public function insertData(TeamRequest $request)
    {
            $image_url = "";
            $teamImage = $this->ImageUpload($request);

            if ($teamImage["status"]) {
                $image_url = $teamImage["data"];
            } else {
                $image_url;
            }           
            
            TeamInfo::create([
                TeamInfo::IMAGE => $image_url,
                TeamInfo::NAME => $request->name,
                TeamInfo::DESIGNATION => $request->designation,
                TeamInfo::POSITION => $request->position,
                TeamInfo::INSTAGRAM => $request->instagram_link,
                TeamInfo::FACEBOOK => $request->facebook_link,
                TeamInfo::TWITTER => $request->twitter_link,
                TeamInfo::STATUS => $request->status,
                TeamInfo::LINKEDIN => $request->linkedin_link,
           ]);

            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(TeamRequest $request)
    {
        
            $team = TeamInfo::find($request->id);
            $image_url = $team->image;
            if($request->file('image')){
                $aboutImage = $this->ImageUpload($request);
                if ($aboutImage["status"]) {
                    $image_url = $aboutImage["data"];
                    $team->image = $image_url;
                } else {
                    return $aboutImage;
                }
            }  
            
            $team->name = $request->name;
            $team->designation = $request->designation;
            $team->position = $request->position;
            $team->instagram_link = $request->instagram_link;
            $team->facebook_link = $request->facebook_link;
            $team->twitter_link = $request->twitter_link;
            $team->linkedin_link = $request->linkedin_link;
            $team->status = 1;
            $team->save();

            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(TeamRequest $request)
    {
        $check = TeamInfo::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(TeamRequest $request)
    {
        $check = TeamInfo::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function getTeamInfo()
    {
        
        $query = TeamInfo::select(
            'image','name','designation','instagram_link' ,'linkedin_link' ,'facebook_link' ,'twitter_link' ,'position','id','status'
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
            ->rawColumns(['action','about_details'])
            ->make(true);
    }


    public function getTeam()
    {
        $teams = TeamInfo::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'teams' => $teams,
        ];

        return response()->json($data, 200);
    }

    public function teamDetail($id)
    {
        $team = TeamInfo::where('id',$id)->first();
        $data = [
            'status' => true,
            'success' => true,
            'team' => $team,
        ];

        return response()->json($data, 200);
    }


}