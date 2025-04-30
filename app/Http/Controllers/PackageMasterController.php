<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\CityMaster;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PackageMaster;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PackageMasterController extends Controller
{
    use CommonFunctions;

    const ACTIVE_PACKAGES = "getActivePackages";
// 
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $city_master = CityMaster::where(CityMaster::STATUS, 1)
                ->get([CityMaster::CITY_NAME, CityMaster::ID]);

            return view("Dashboard.PackageMaster.viewPackages", compact("city_master"));
        } catch (Exception $exception) {
            report($exception);
            return back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Store a new or updated package.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "id" => "nullable|exists:package_master,id",
                "package_name" => "required|string|unique:package_master,package_name," . $request->id . ",id",
                "package_country" => "required|string",
                "package_image.*" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
                "description" => "nullable|string|max:65535",
                "meta_keyword"=>"bail|nullable",
                "meta_title"=>"bail|nullable",
                "meta_description"=>"bail|nullable",
            ]);

            if ($validator->fails()) {
                return response()->json(["status" => false, "message" => $validator->errors()->first()]);
            }

            DB::beginTransaction();

            $package = $request->id
                ? PackageMaster::find($request->id)
                : new PackageMaster();

            // Image Upload
            if ($request->hasFile('package_image')) {
                $images = $this->uploadMultipleImages($request, 'package_image');
                $package->package_image = $images;
            }

            $package->package_name = $request->package_name;
            $package->package_country = $request->package_country;
            $package->description = $request->description;
            $package->meta_keyword = $request->meta_keyword;
            $package->meta_title = $request->meta_title;
            $package->meta_description = $request->meta_description;
            $package->status = 1;

            if ($request->id) {
                $package->updated_by = Auth::id();
            } else {
                $package->created_by = Auth::id();
            }

            $package->save();

            Cache::forget(self::ACTIVE_PACKAGES);
            DB::commit();

            return response()->json(["status" => true, "message" => "Package details saved successfully."]);
        } catch (Exception $exception) {
            DB::rollBack();
            report($exception);
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    /**
     * Edit a package.
     */
    // public function edit($id)
    // {
    //     try {
    //         $packageData = PackageMaster::where([['id', $id], ['status', 1]])
    //             ->firstOrFail();

    //         $city_master = CityMaster::where(CityMaster::STATUS, 1)
    //             ->get([CityMaster::CITY_NAME, CityMaster::ID]);

    //         return view("Dashboard.PackageMaster.editPackage", compact("packageData", "city_master"));
    //     } catch (Exception $exception) {
    //         report($exception);
    //         return back()->withErrors($exception->getMessage());
    //     }
    // }
    public function edit($id)
    {
        $packageData = PackageMaster::where([
            [PackageMaster::ID, $id],
            [PackageMaster::STATUS, 1]
        ])->firstOrFail();

        // $package_types = PackageMaster::PACKAGE_TYPES;
        // $city_master = CityMaster::where(CityMaster::STATUS, 1)->get([CityMaster::CITY_NAME, CityMaster::ID]);

        return view("Dashboard.PackageMaster.editPackage", compact( 'packageData'));
    }
    public function getActivePackages()
{
    try {
        // Retrieve from cache if available
        $packageData = Cache::get(self::ACTIVE_PACKAGES);

        if (empty($packageData)) {
            // Fetch active packages
            $packages = PackageMaster::where(PackageMaster::STATUS, 1)->get();

            if ($packages->isNotEmpty()) {
                $packageData = $packages;

                // Cache the result
                Cache::rememberForever(self::ACTIVE_PACKAGES, function () use ($packageData) {
                    return $packageData;
                });
            }
        }

        return $packageData;
    } catch (Exception $exception) {
        report($exception);
        return null; // Return null or handle as per your application's error-handling policy
    }
}



    /**
     * Enable or disable a package.
     */
    public function enableDisablePackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "action" => "required|in:enable,disable",
            "id" => "required|exists:package_master,id",
        ]);

        if ($validator->fails()) {
            return response()->json(["status" => false, "message" => $validator->errors()->first()]);
        }

        try {
            $package = PackageMaster::findOrFail($request->id);

            $package->status = $request->action === "enable" ? 1 : 0;
            $package->updated_by = Auth::id();
            $package->save();

            Cache::forget(self::ACTIVE_PACKAGES);

            $message = $request->action === "enable"
                ? "Package enabled successfully."
                : "Package disabled successfully.";

            return response()->json(["status" => true, "message" => $message]);
        } catch (Exception $exception) {
            report($exception);
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    /**
     * Handle the DataTable for listing packages.
     */
    public function dataTable()
    {
        try {
            $query = PackageMaster::select([
                'id', 'package_name', 'package_image', 'package_country', 'status', 'description','meta_title','meta_keyword','meta_description'
            ]);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('package_image', function ($row) {
                    $images = is_string($row->package_image)
                        ? json_decode($row->package_image, true)
                        : $row->package_image;

                    if (is_array($images) && !empty($images)) {
                        $html = '';
                        foreach ($images as $image) {
                            $html .= '<img alt="Image" src="' . asset('storage/' . $image) . '" class="img-thumbnail" style="max-width: 100px;"> ';
                        }
                        return $html;
                    }
                    return 'No Images Available';
                })
                ->addColumn('description', function ($row) {
                    return nl2br(e($row->description));
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route("packageMaster.edit", $row->id);
                    $btnEdit = '<a href="' . $editUrl . '" class="btn btn-primary btn-sm">Edit</a>';
                    $btnToggle = $row->status == 1
                        ? '<a href="javascript:void(0)" onclick="Disable(' . $row->id . ')" class="btn btn-danger btn-sm">Disable</a>'
                        : '<a href="javascript:void(0)" onclick="Enable(' . $row->id . ')" class="btn btn-success btn-sm">Enable</a>';

                    return $btnEdit . ' ' . $btnToggle;
                })
                ->rawColumns(['package_image', 'description', 'action'])
                ->make(true);
        } catch (Exception $exception) {
            report($exception);
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    /**
     * Upload multiple images.
     */
    private function uploadMultipleImages(Request $request, $fieldName)
    {
        $images = [];
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('website/uploads/package_images', $filename, 'public');
                $images[] = $path;
            }
        }
        return $images;
    }
}
