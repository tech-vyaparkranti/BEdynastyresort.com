<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    use CommonFunctions;

    public function viewCategory()
    {
        return view("Dashboard.Pages.manageCategory");
    }

    public function saveCategory(CategoryRequest $request)
    {
        Cache::forget("categories");
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

    public function ImageUpload(CategoryRequest $request)
    {
        $maxId = Category::max(Category::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, "image", "/images/gallery/", "gallery_$maxId");
    }

    public function insertData(CategoryRequest $request)
    {         
            $createNewRow = new Category();
            
            $createNewRow->status = $request->status;
            $createNewRow->tab_name = $request->tab_name;
            $createNewRow->category_name = $request->category_name;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(CategoryRequest $request)
    {
            $updateModel = Category::find($request->id);    
            
            $updateModel->status = $request->status;
            $updateModel->tab_name = $request->tab_name;
            $updateModel->category_name = $request->category_name;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(CategoryRequest $request)
    {
        $check = Category::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(CategoryRequest $request)
    {
        $check = Category::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function categoryData()
    {

        $query = Category::select(
            'tab_name' ,'category_name','status','id'
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

    public function getCategory()
    {
        $categories = Category::where('status',1)->get();
        $roomCategory = Category::where("status",1)->where('tab_name','Room')->get();
        $guestCategory = Category::where("status",1)->where('tab_name','Guest Experience')->get();

        $data = [
            'status' => true,
            'success' => true,
            'categories' => $categories,
            'roomCategory' => $roomCategory,
            'guestCategory' => $guestCategory,
        ];
        return response()->json($data, 200);
    }
}
