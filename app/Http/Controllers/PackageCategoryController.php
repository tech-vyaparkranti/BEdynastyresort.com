<?php

namespace App\Http\Controllers;

use App\Models\PackageCategoriesModel;
use App\Models\PackageMaster;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PackageCategoryController extends Controller
{
    //
    use CommonFunctions;
    const PACKAGE_CATEGORY_KEY = "package_categories";
    public function managePackageCategories()
    {
        $categories = PackageCategoriesModel::CATEGORIES;
        $packages = PackageMaster::where(PackageMaster::STATUS, 1)->get([PackageMaster::ID, PackageMaster::PACKAGE_NAME]);
        return view("Dashboard.Pages.managePackageCategory", compact("categories", "packages"));
    }

    public function addPackageCategoryData(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    "id" => "nullable|bail|required_if:action,edit,enable,disable",
                    "category_name" => "required_if:action,edit,insert|bail|nullable|in:" . implode(",", PackageCategoriesModel::CATEGORIES),
                    "package_id"=>"required_if:action,edit,insert|nullable|array",
                    "package_id.*" => [
                        Rule::exists(PackageMaster::TABLE_NAME, PackageMaster::ID)->where(PackageMaster::STATUS, 1)
                    ],
                    "position" => "required_if:action,edit,insert|integer|gt:0",
                ],
                
            );
            if($validation->fails()){
                $return = ["status"=>false,"message"=>$validation->getMessageBag()->first(),"data"=>null];
            }else{
                Cache::forget(self::PACKAGE_CATEGORY_KEY);
                if(in_array($request->action,["enable","disable"]) ){
                    $return = $this->enableDisable($request);
                }else{
                    $allPrevPackages = PackageCategoriesModel::where(PackageCategoriesModel::CATEGORY_NAME,$request->category_name)->get();
                    DB::beginTransaction();
                    $insert = [];
                    $position = $request->position;
                    if(empty($position)){
                        $position = PackageCategoriesModel::max(PackageCategoriesModel::POSITION);
                    }
                    foreach($request->package_id as $pk){
                        $check = $allPrevPackages->where(PackageCategoriesModel::PACKAGE_ID,$pk)->first();
                        if($check){
                            PackageCategoriesModel::where(PackageCategoriesModel::ID,$check->id)
                            ->update([
                                PackageCategoriesModel::STATUS=>1,
                                PackageCategoriesModel::POSITION=>$position,
                                PackageCategoriesModel::UPDATED_BY=>Auth::user()->id,
                            ]);
                        }else{
                            $insert [] = [
                                PackageCategoriesModel::CATEGORY_NAME=>$request->category_name,
                                PackageCategoriesModel::PACKAGE_ID=>$pk,
                                PackageCategoriesModel::STATUS=>1,
                                PackageCategoriesModel::POSITION=>$position,
                                PackageCategoriesModel::CREATED_BY=>Auth::user()->id,
                            ];
                        }
                    }
                    if(count($insert)){
                        PackageCategoriesModel::insert($insert);
                    }
                    $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
                    DB::commit();
                }
                
            }
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);
            $return = ["status"=>false,"message"=>"Something went wrong","data"=>null];
        }
        return response()->json($return);
    }

    public function packageCategoryData(){
        
        $query = PackageCategoriesModel::select(
            PackageCategoriesModel::CATEGORY_NAME,
            PackageCategoriesModel::ID,
            PackageCategoriesModel::POSITION,
            PackageCategoriesModel::STATUS,
            PackageCategoriesModel::PACKAGE_ID
        )->with("package");
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn_edit = '<a  href="javascript:void(0)"  data-row="'.base64_encode(json_encode($row)).'"   class="edit btn btn-primary btn-sm mt-2">Edit</a>';

                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable(' . $row->{PackageCategoriesModel::ID} . ')" class="btn btn-danger btn-sm mt-2">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable(' . $row->{PackageCategoriesModel::ID} . ')" class="btn btn-primary btn-sm mt-2">Enable</a>';
                if ($row->{PackageCategoriesModel::STATUS} == 1) {
                    return '<div class="row">'.$btn_edit . $btn_disable.'</div>';
                } else {
                    return '<div class="row">'.$btn_edit . $btn_enable.'</div>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function enableDisable(Request $request){
        $validator = Validator::make($request->all(),[
            "action"=>"required|in:enable,disable",
            "id"=>"required"
        ]);
        if($validator->fails()){
            return $this->returnMessage($validator->getMessageBag()->first());
        }
        $check = PackageCategoriesModel::where(PackageCategoriesModel::ID, $request->input(PackageCategoriesModel::ID))->first();
        if ($check) {
            if($request->action=="disable"){
                $check->{PackageCategoriesModel::STATUS} = 0;
                $check->{PackageCategoriesModel::UPDATED_BY} = Auth::user()->id;
                $check->save();
                 
                $return = $this->returnMessage("Disabled successfully.", true);
            }elseif($request->action=="enable"){
                $check->{PackageCategoriesModel::STATUS} = 1;
                $check->{PackageCategoriesModel::UPDATED_BY} = Auth::user()->id;
                $check->save();
                 
                $return = $this->returnMessage("Enabled successfully.", true);
            }else{
                $return = $this->returnMessage("Undefined action.");
            }
            
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function getActivePackagesCategoryData(){
        $packageCategory = Cache::get(self::PACKAGE_CATEGORY_KEY);
        if(empty($packageCategory)){
            $packageCategory = PackageCategoriesModel::where(PackageCategoriesModel::STATUS,1)
            ->has("package")
            ->with("package")
            ->with("package.itinerary")
            ->with("package.itinerary.city")
            ->orderBy(PackageCategoriesModel::POSITION,"asc")->get();
            if(count($packageCategory)){
                $packageCategory = collect($packageCategory)->groupBy(PackageCategoriesModel::CATEGORY_NAME)->toArray();
                if(!empty($packageCategory)){
                    Cache::rememberForever(self::PACKAGE_CATEGORY_KEY, function () use($packageCategory) {
                        return $packageCategory;
                    });
                }
            }
        }
        return $packageCategory;
    }
}
