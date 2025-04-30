<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DownloadFile;
use Yajra\DataTables\Facades\DataTables;


class DownloadFileController extends Controller
{
    public function uploadFile()
    {
        return view('Dashboard.Pages.downloadFile');
    }


    public function saveFile(Request $request)
    {
        try {
            $IMAGE_UPLOAD_PATH = "/upload/gallery/downloadImages/";
            $FILE_UPLOAD_PATH = "/upload/gallery/downloadFile/";
            $maxId = DownloadFile::max(DownloadFile::ID);
            $maxId = ($maxId) ? $maxId + 1 : 1;  
            $insert = [];
    
            if ($request->hasFile(DownloadFile::LOCAL_FILE)) {  
                $file = $request->file(DownloadFile::LOCAL_FILE);
    
                $fileName = $file->getClientOriginalName();
                $fileName = "Img_$maxId" . preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
    
                $file->move(public_path($FILE_UPLOAD_PATH), $fileName);
    
                $insert[DownloadFile::LOCAL_FILE] = $FILE_UPLOAD_PATH . $fileName;
                $maxId++; 
            }
            if ($request->hasFile(DownloadFile::IMAGE)) {
                $image = $request->file(DownloadFile::IMAGE);
                $imageName = $image->getClientOriginalName();
    
                $imageName = "Img_$maxId" . preg_replace('/[^A-Za-z0-9.\-]/', '', $imageName);
    
                $image->move(public_path($IMAGE_UPLOAD_PATH), $imageName);
    
                $insert[DownloadFile::IMAGE] = $IMAGE_UPLOAD_PATH . $imageName;
                $maxId++; // Increment for the next entry
            }
    
            if (!empty($insert)) {
                DownloadFile::create([
                    DownloadFile::LOCAL_FILE => $insert[DownloadFile::LOCAL_FILE],
                    DownloadFile::IMAGE => isset($insert[DownloadFile::IMAGE]) ? $insert[DownloadFile::IMAGE] : null,
                    DownloadFile::TITLE => $request->title,
                    DownloadFile::STATUS => $request->status,
                ]);
            }
           $maxId++;
            // $response = [
            //     'success' => true,
            //     'message' => "File uploaded successfully",
            // ];
            return $this->returnMessage("Saved successfully.", true);
    
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Upload failed", 
                'error' => $th->getMessage()
            ], 422);
        }
    }
    public function getFilesData()
    {
        $query = DownloadFile::select(
            DownloadFile::LOCAL_FILE,
            DownloadFile::IMAGE,
            DownloadFile::TITLE,
            DownloadFile::STATUS,
            DownloadFile::SORTING,
            DownloadFile::ID
        );
        return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm mt-2">Edit</a>';
            $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->{DownloadFile::ID} . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
            $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->{DownloadFile::ID} . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
            if ($row->{DownloadFile::STATUS} == "enable") {
                return $this->addDiv($btn_edit . $btn_disable);
            } else {
                return $this->addDiv($btn_edit . $btn_enable);
            }
        })
        ->rawColumns(['action','service_details'])
        ->make(true);
    }

    public function editFilesData(Request $request ,$id)
    {

    }

    public function addDiv($item,$class="row",$id=""){
        return '<div class="'.$class.'" id="'.$id.'">'.$item.'</div>';
    }
    
    public function uploadData(Request $request)
    {
        switch ($request->input("action")) {
            case "insert": 
                return $this->saveFile($request);
                break;
            case "update": 
                return $this->updateDownloadFile($request);
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
    public function disableRow(Request $request)
    {
        $check = DownloadFile::where(DownloadFile::ID, $request->input(DownloadFile::ID))->first();
        if ($check) {
            $check->{DownloadFile::STATUS} = "disable";
            // $check->{DownloadFile::UPDATED_BY} = Auth::user()->id;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }
    public function enableRow(Request $request)
    {
        $check = DownloadFile::where(DownloadFile::ID, $request->input(DownloadFile::ID))->first();
        if ($check) {
            $check->{DownloadFile::STATUS} = "enable";
            // $check->{DownloadFile::UPDATED_BY} = Auth::user()->id;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }
    public function updateDownloadFile(Request $request){
        try{
            $downloadFile = DownloadFile::where([
                [DownloadFile::ID,$request->input(DownloadFile::ID)],
                [DownloadFile::STATUS,"enable"]])->first();
            $IMAGE_UPLOAD_PATH = "/upload/gallery/downloadImages/";
            $FILE_UPLOAD_PATH = "/upload/gallery/downloadFile/";
            $maxId = DownloadFile::max(DownloadFile::ID);
            $maxId = ($maxId) ? $maxId + 1 : 1;  
            if($downloadFile){
                if ($request->hasFile(DownloadFile::LOCAL_FILE)) {  
                    $file = $request->file(DownloadFile::LOCAL_FILE);
        
                    $fileName = $file->getClientOriginalName();
                    $fileName = "Img_$maxId" . preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
        
                    $file->move(public_path($FILE_UPLOAD_PATH), $fileName);
        
                    $downloadFile->local_file = $FILE_UPLOAD_PATH . $fileName;
                    $maxId++; 
                }
                if ($request->hasFile(DownloadFile::IMAGE)) {
                    $image = $request->file(DownloadFile::IMAGE);
                    $imageName = $image->getClientOriginalName();
        
                    $imageName = "Img_$maxId" . preg_replace('/[^A-Za-z0-9.\-]/', '', $imageName);
        
                    $image->move(public_path($IMAGE_UPLOAD_PATH), $imageName);
        
                    $downloadFile->image = $IMAGE_UPLOAD_PATH . $imageName;
                    $maxId++; 
                }
                $downloadFile->title = $request->title;
                $downloadFile->status = $request->status;
                $maxId++;
                $downloadFile->save();
                // $return = ["status"=>true,"message"=>"Updated successfully","data"=>"null"];  
            }else{
                $return = ["status"=>false,"message"=>"Not found.","data"=>"null"];
            }    
            return $this->returnMessage("update successfully.", true);
        }catch(Exception $exception){
            $this->reportException($exception);
            return ["status"=>false,"message"=>"Something went wrong.","data"=>"null"];
        }
    }

    public function returnMessage($message,$status=false,$data = []){
        return ["status"=>$status,"message"=>$message,"data"=>$data];
    }


}
