<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\OfferRequest;
use App\Models\Offer;
use App\Traits\CommonFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
{
    use CommonFunctions;

    public function viewOffer()
    {
        return view("Dashboard.Pages.manageOffers");
    }

    public function saveOffer(OfferRequest $request)
    {
        Cache::forget("offers");
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

    public function ImageUpload(OfferRequest $request)
    {
        $maxId = Offer::max(Offer::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request, "image", "/images/gallery/", "gallery_$maxId");
    }

    public function insertData(OfferRequest $request)
    {         
            $createNewRow = new Offer();
            if($request->hasFile('image'))
            {
                $imageUrl = "";
                $image = $this->ImageUpload($request);
                if($image['status'])
                {
                    $imageUrl = $image['data'];
                }
                else{
                    return $image;
                }
            }
            if($request->features)
            {
                $features = [];
                foreach ($request->features as $value) {
                    $features[] = $value;
                }
            }
            $createNewRow->status = $request->status;
            $createNewRow->features = json_encode($features);
            $createNewRow->image = $imageUrl;
            $createNewRow->offer_price = $request->offer_price;
            $createNewRow->title = $request->title;
            $createNewRow->price = $request->price;
            $createNewRow->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function updateData(OfferRequest $request)
    {
            $updateModel = Offer::find($request->id);    
            
            $imageUrl = $updateModel->image;

            if($request->hasFile('image'))
            {
                $image = $this->ImageUpload($request);
                if($image['status'])
                {
                    $imageUrl = $image['data'];
                }
                
            }
            if($request->features)
            {
                $features = [];
                foreach ($request->features as $value) {
                    $features[] = $value;
                }
            }
            $updateModel->title = $request->title;
            $updateModel->status = $request->status;
            $updateModel->features = json_encode($features);
            $updateModel->status = $request->status;
            $updateModel->image = $imageUrl;
            $updateModel->price = $request->price;
            $updateModel->offer_price = $request->offer_price;
            $updateModel->save();
            $return = $this->returnMessage("Saved successfully.", true);
        
        return $return;
    }

    public function enableRow(OfferRequest $request)
    {
        $check = Offer::where('id', $request->id)->first();
        if ($check) {
            $check->status = 1;
            $check->save();
             
            $return = $this->returnMessage("Enabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function disableRow(OfferRequest $request)
    {
        $check = Offer::where('id', $request->id)->first();
        if ($check) {
            $check->status = 0;
            $check->save();
             
            $return = $this->returnMessage("Disabled successfully.", true);
        } else {
            $return = $this->returnMessage("Details not found.");
        }
        return $return;
    }

    public function offerData()
    {

        $query = Offer::select(
            'features' ,'status','offer_price' ,'price','image','title','id'
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


    public function offerApi()
    {
        $rooms = Offer::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'rooms' => $rooms,
        ];
        return response()->json($data, 200);
    }
}
