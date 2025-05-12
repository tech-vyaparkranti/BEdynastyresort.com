<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebSiteElementRequest;
use App\Models\WebSiteElements;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WebSiteElementsController extends Controller
{
    
    use CommonFunctions;
    const ELEMENTS = [
        "logo",
        "address",
        "mail",
        "mobile",
        "whatsapp_number",
        "map_link",
        "facebook_link",
        "youtube_link",
        "instagram_link",
        "twitter_link",
        "linkedin_link",

        "about_banner_heading",
        "about_banner",
        "about_banner_description",

        "room_banner_heading",
        "room_banner_description",
        "room_banner",

        "event_banner_heading",
        "event_banner",
        "event_banner_description",

        "wedding_banner_heading",
        "wedding_banner",
        "wedding_banner_description",


        "satsang_banner_heading",
        "satsang_banner",
        "satsang_banner_description",

        "guest_banner_heading",
        "guest_banner",
        "guest_banner_description",


        "gallery_banner_heading",
        "gallery_banner",
        "gallery_banner_description",


        "offer_banner_heading",
        "offer_banner",
        "offer_banner_description",

        "activity_banner",
        "activity_banner_heading",
        "activity_banner_description",

        "blog_banner",
        "blog_banner_heading",
        "blog_banner_description",

        "contact_banner",
        "contact_banner_heading",
        "contact_banner_description",

        "service_banner",
        "service_banner_heading",
        "service_banner_description",

        "restaurant_banner",
        "restaurant_banner_heading",
        "restaurant_banner_description",


        "facility_banner",
        "home_video_link",
        "footer_content",
        "home_video_link_image",
        'enquiry_banner_image',

         "package_banner",
        "package_banner_heading",
        "package_banner_description",
    ];
    public function addWebSiteElements()
    {
        $titles = self::ELEMENTS;
        return view("Dashboard.Pages.webSiteElements", compact("titles"));
    }

    public function saveWebSiteElement(WebSiteElementRequest $request)
    {
        try {
            $requestData = $request->all();
            if ($requestData["action"] == "insert") {
                $return = $this->insertWebSiteElement($requestData, $request);
            } else if ($request["action"] == "update") {
                $return = $this->updateWebSiteElement($requestData, $request);
            } else if ($request["action"] == "disable") {
                $check = WebSiteElements::where(WebSiteElements::ID, $requestData[WebSiteElements::ID])->first();
                $check->{WebSiteElements::UPDATED_BY} = Auth::user()->id;
                $check->{WebSiteElements::STATUS} = 0;
                $check->save();
                $this->forgetWebSiteElements();
                $return = ["status" => true, "message" => "Details updated", "data" => null];
            } else if ($request["action"] == "enable") {
                $check = WebSiteElements::where(WebSiteElements::ID, $requestData[WebSiteElements::ID])->first();
                $check->{WebSiteElements::UPDATED_BY} = Auth::user()->id;
                $check->{WebSiteElements::STATUS} = 1;
                $check->save();
                $this->forgetWebSiteElements();
                $return = ["status" => true, "message" => "Details updated", "data" => null];
            } else {
                $return = ["status" => false, "message" => "Invalid action", "data" => null];
            }
        } catch (Exception $exception) {
            $return = ["status" => false, "message" => "Exception occurred  : " . $exception->getMessage(), "data" => null];
        }
        return response()->json($return);
    }

    public function updateWebSiteElement($requestData, WebSiteElementRequest $request)
    {
        $check = WebSiteElements::where(WebSiteElements::ID, $requestData[WebSiteElements::ID])->first();
        if ($check) {
            if ($this->checkDuplicateElement($requestData[WebSiteElements::ELEMENT], $requestData[WebSiteElements::ID])) {
                $return = ["status" => false, "message" => "Element already found", "data" => null];
            } else {
                $check->{WebSiteElements::ELEMENT} = $requestData[WebSiteElements::ELEMENT];
                $check->{WebSiteElements::ELEMENT_TYPE} = $requestData[WebSiteElements::ELEMENT_TYPE];
                if ($requestData[WebSiteElements::ELEMENT_TYPE] == "Image") {
                    $fileUpload = $this->uploadLocalFile($request, "element_details_image", "/images/WesiteElements/");
                    if ($fileUpload["status"]) {
                        $check->{WebSiteElements::ELEMENT_DETAILS} = $fileUpload["data"];
                    } else {
                        return $fileUpload;
                    }
                } else {
                    $check->{WebSiteElements::ELEMENT_DETAILS} = $requestData["element_details_text"];
                }
                $check->save();
                $this->forgetWebSiteElements();
                $return = ["status" => true, "message" => "Details updated", "data" => null];
            }
        } else {
            $return = ["status" => false, "message" => "Details not found", "data" => null];
        }
        return $return;
    }

    public function checkDuplicateElement($element, $existingId = null)
    {
        $check = WebSiteElements::where(WebSiteElements::ELEMENT, $element);
        if ($existingId) {
            $check->where(WebSiteElements::ID, "!=", $existingId);
        }
        return $check->exists();
    }
    public function insertWebSiteElement($requestData, WebSiteElementRequest $request)
    {
        $check = WebSiteElements::where([
            [WebSiteElements::ELEMENT, $requestData[WebSiteElements::ELEMENT]],
            [WebSiteElements::ELEMENT_TYPE, $requestData[WebSiteElements::ELEMENT_TYPE]]
        ])->first();
        if ($this->checkDuplicateElement($requestData[WebSiteElements::ELEMENT])) {
            $return = ["status" => false, "message" => "Element already found", "data" => null];
        } else {
            $check = new WebSiteElements();
            $check->{WebSiteElements::ELEMENT} = $requestData[WebSiteElements::ELEMENT];
            $check->{WebSiteElements::ELEMENT_TYPE} = $requestData[WebSiteElements::ELEMENT_TYPE];
            if ($requestData[WebSiteElements::ELEMENT_TYPE] == "Image") {
                $fileUpload = $this->uploadLocalFile($request, "element_details_image", "/images/WesiteElements/");
                if ($fileUpload["status"]) {
                    $check->{WebSiteElements::ELEMENT_DETAILS} = $fileUpload["data"];
                } else {
                    return $fileUpload;
                }
            } else {
                $check->{WebSiteElements::ELEMENT_DETAILS} = $requestData["element_details_text"];
            }
            $this->forgetWebSiteElements();
            $check->save();
            $return = ["status" => true, "message" => "Saved successfully.", "data" => null];
        }
        return $return;
    }


    public function getWebElementsData()
    {
        $data = WebSiteElements::select(
            WebSiteElements::ID,
            WebSiteElements::ELEMENT,
            WebSiteElements::ELEMENT_TYPE,
            WebSiteElements::ELEMENT_DETAILS,
            WebSiteElements::STATUS
        );

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                if ($row->{WebSiteElements::STATUS} == 1) {
                    $btn .= '<a href="javascript:void(0)" onclick="Disable(\''.$row->{WebSiteElements::ID}.'\')" class="btn btn-danger btn-sm">Disable</a>';
                } else {
                    $btn .= '<a href="javascript:void(0)" onclick="Enable(\''.$row->{WebSiteElements::ID}.'\')" class="btn btn-info btn-sm">Enable</a>';
                }
                return $btn;
            })
            ->rawColumns(['action',WebSiteElements::ELEMENT_DETAILS])
            ->make(true);
    }


    public function homeElements()
    {

        $elements = WebSiteElements::where('status','1')->get();
        $elementData = $elements->pluck('element_details', 'element')->toArray();
        $data = [
            'status' => true,
            'success' => true,
            'elements' => $elementData,
        ];
        return response()->json($data, 200);
    }

    public function topHeaderElement()
    {
        $mail = WebSiteElements::where('status','1')->where('element','mail')->value("element_details");
        $mobile = WebSiteElements::where('status','1')->where('element','mobile')->value("element_details");
        $address = WebSiteElements::where('status','1')->where('element','address')->value("element_details");
        $logo = WebSiteElements::where('status','1')->where('element','logo')->value("element_details");
        $data = [
            'status' => true,
            'success' => true,
            'mail' => $mail,
            "mobile" => $mobile,
            "address" => $address,
            "logo" =>$logo,
        ];
        return response()->json($data, 200);
    }

    public function contactElements()
    {
        $mail = WebSiteElements::where('status','1')->where('element','mail')->value("element_details");
        $mobile = WebSiteElements::where('status','1')->where('element','mobile')->value("element_details");
        $address = WebSiteElements::where('status','1')->where('element','address')->value("element_details");
        $facebook = WebSiteElements::where('status','1')->where('element','facebook_link')->value("element_details");
        $youtube = WebSiteElements::where('status','1')->where('element','youtube_link')->value("element_details");
        $instagram = WebSiteElements::where('status','1')->where('element','instagram_link')->value("element_details");
        $twitter = WebSiteElements::where('status','1')->where('element','twitter_link')->value("element_details");
        $linkedin = WebSiteElements::where('status','1')->where('element','linkedin_link')->value("element_details");
        $map = WebSiteElements::where('status','1')->where('element','mao_link')->value("element_details");
        $footer_content = WebSiteElements::where('status','1')->where('element','footer_content')->value("element_details");
        $logo = WebSiteElements::where('status','1')->where('element','logo')->value("element_details");

        $data = [
            'status' => true,
            'success' => true,
            'mail' => $mail,
            "mobile" => $mobile,
            "map" => $map , 'footer_content' => $footer_content,'logo' => $logo,
            "address" => $address,'linkedin' =>$linkedin,
            "facebook" =>$facebook, 'youtube' => $youtube ,'instagram' => $instagram ,'twitter' => $twitter
        ];
        return response()->json($data, 200);
    }

    public function socialMedia()
    {
        
        $facebook = WebSiteElements::where('status','1')->where('element','facebook_link')->value("element_details");
        $youtube = WebSiteElements::where('status','1')->where('element','youtube_link')->value("element_details");
        $instagram = WebSiteElements::where('status','1')->where('element','instagram_link')->value("element_details");
        $twitter = WebSiteElements::where('status','1')->where('element','twitter_link')->value("element_details");
        $linkedin = WebSiteElements::where('status','1')->where('element','linkedin_link')->value("element_details");

        $data = [
            'status' => true,
            'success' => true,
            'linkedin' =>$linkedin,
            "facebook" =>$facebook, 'youtube' => $youtube ,'instagram' => $instagram ,'twitter' => $twitter
        ];
        return response()->json($data, 200);
    }

    public function heroBanner()
    {
        $requiredElements = [
            "about_banner_heading",
            "about_banner",
            "room_banner_heading",
            "room_banner",
            "event_banner_heading",
            "event_banner",
            "wedding_banner_heading",
            "wedding_banner",
            "satsang_banner_heading",
            "satsang_banner",
            "guest_banner_heading",
            "guest_banner",
            "gallery_banner_heading",
            "gallery_banner",
            "offer_banner_heading",
            "offer_banner",

            "about_banner_description",
            "room_banner_description",
            "event_banner_description",
            "wedding_banner_description",
            "satsang_banner_description",
            "guest_banner_description",
            "gallery_banner_description",
            "offer_banner_description",

            
            "activity_banner",
            "activity_banner_heading",
            "activity_banner_description",

            "blog_banner",
            "blog_banner_heading",
            "blog_banner_description",

            "contact_banner",
            "contact_banner_heading",
            "contact_banner_description",

            "service_banner",
            "service_banner_heading",
            "service_banner_description",

            "restaurant_banner",
            "restaurant_banner_heading",
            "restaurant_banner_description",

            "package_banner",
            "package_banner_heading",
            "package_banner_description",

        ];
        
        $bannerData = WebSiteElements::where('status', 1)
            ->whereIn('element', $requiredElements)
            ->pluck('element_details', 'element')
            ->toArray();
        $cleanedBannerData = array_map('strip_tags', $bannerData);

            $data = [
                'status' => true,
                'success' => true,
                'bannerData' => $cleanedBannerData,
            ];
            return response()->json($data, 200);
    }

    public function videoLink()
    {
        $videoLink = WebSiteElements::where('status','1')->where('element','home_video_link')->value("element_details");
        $bannerImage = WebSiteElements::where('status','1')->where('element','home_video_link_image')->value("element_details");

        $data = [
            'status' => true,
            'success' => true,
            'videoLink' => $videoLink,
            'bannerImage' => $bannerImage,
        ];
        return response()->json($data, 200);
        
    }

    public function enquiryImage()
    {
        $enquiryImage = WebSiteElements::where('status','1')->where('element','enquiry_banner_image')->value("element_details");

        $data = [
            'status' => true,
            'success' => true,
            'enquiryImage' => $enquiryImage,
        ];
        return response()->json($data, 200);
    }
}
