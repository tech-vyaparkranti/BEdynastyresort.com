<?php

namespace App\Http\Controllers;

use App\Http\Requests\DonateRequest;
use App\Models\DonateModel;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class DonateController extends Controller
{
    use CommonFunctions;

    public function donate()
    {
        return view("Dashboard.Pages.donateAdmin");
    }

    // public function donateData()
    // {
    //     try {
    //         $query = DonateModel::select(
    //             DonateModel::IMAGE,
    //             DonateModel::ID,
    //             DonateModel::HEADING_BOTTOM,
    //             DonateModel::SLIDE_SORTING,
    //             DonateModel::SLIDE_STATUS
    //         );

    //         return DataTables::of($query)
    //             ->addIndexColumn()
    //             ->addColumn('heading_top', function ($row) {
    //                 $modalId = "modal-heading-top-{$row->id}";
    //                 $shortHeadingTop = Str::limit(strip_tags(implode(', ', json_decode($row->heading_top) ?? [])), 50, '...');
    //                 $fullHeadingTop = nl2br(e(implode('<br>', json_decode($row->heading_top) ?? [])));
                
    //                 return '
    //                     <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
    //                         View Heading Top
    //                     </button>
    //                     <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $modalId . '-label" aria-hidden="true">
    //                         <div class="modal-dialog modal-dialog-centered">
    //                             <div class="modal-content">
    //                                 <div class="modal-header">
    //                                     <h5 class="modal-title" id="' . $modalId . '-label">Heading Top</h5>
    //                                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    //                                 </div>
    //                                 <div class="modal-body">
    //                                     ' . $fullHeadingTop . '
    //                                 </div>
    //                                 <div class="modal-footer">
    //                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 ';
    //             })
    //             ->addColumn('heading_middle', function ($row) {
    //                 $modalId = "modal-heading-middle-{$row->id}";
    //                 $shortHeadingMiddle = Str::limit(strip_tags(implode(', ', json_decode($row->heading_middle) ?? [])), 50, '...');
    //                 $fullHeadingMiddle = nl2br(e(implode('<br>', json_decode($row->heading_middle) ?? [])));
                
    //                 return '
    //                     <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
    //                         View Heading Middle
    //                     </button>
    //                     <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $modalId . '-label" aria-hidden="true">
    //                         <div class="modal-dialog modal-dialog-centered">
    //                             <div class="modal-content">
    //                                 <div class="modal-header">
    //                                     <h5 class="modal-title" id="' . $modalId . '-label">Heading Middle</h5>
    //                                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    //                                 </div>
    //                                 <div class="modal-body">
    //                                     ' . $fullHeadingMiddle . '
    //                                 </div>
    //                                 <div class="modal-footer">
    //                                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 ';
    //             })
                
    //             ->addColumn('heading_bottom', function ($row) {
    //                 return $this->generateModalContent($row, 'heading_bottom', 'Heading Bottom');
    //             })
                
    //             ->addColumn('action', function ($row) {
    //                 return $this->generateActionButtons($row);
    //             })
    //             ->rawColumns(['heading_top', 'heading_middle', 'heading_bottom', 'action'])
    //             ->make(true);
    //     } catch (Exception $exception) {
    //         return response()->json(['status' => false, 'message' => $exception->getMessage()]);
    //     }
    // }

  public function donateData()
{
    try {
        $query = DonateModel::select(
            DonateModel::ID,
            DonateModel::IMAGE,
            DonateModel::HEADING_TOP,
            // DonateModel::HEADING_MIDDLE,
            // DonateModel::HEADING_BOTTOM,
            DonateModel::SLIDE_SORTING,
            DonateModel::SLIDE_STATUS
        );

        return DataTables::of($query)
            ->addIndexColumn()
            // ->addColumn('heading_top', function ($row) {
            //     $modalId = "modal-heading-top-{$row->id}";
            //     $headings = $row->heading_top ?? [];
            //     $shortHeadingTop = Str::limit(implode(', ', $headings), 50, '...');
            //     $fullHeadingTop = nl2br(e(implode('<br>', $headings)));

            //     return '
            //         <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
            //             View Heading Top
            //         </button>
            //         <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $modalId . '-label" aria-hidden="true">
            //             <div class="modal-dialog modal-dialog-centered">
            //                 <div class="modal-content">
            //                     <div class="modal-header">
            //                         <h5 class="modal-title" id="' . $modalId . '-label">Heading Top</h5>
            //                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            //                     </div>
            //                     <div class="modal-body">
            //                         ' . $fullHeadingTop . '
            //                     </div>
            //                     <div class="modal-footer">
            //                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            //                     </div>
            //                 </div>
            //             </div>
            //         </div>
            //     ';
            // })
            // ->addColumn('heading_middle', function ($row) {
            //     $modalId = "modal-heading-middle-{$row->id}";
            //     $headings = $row->heading_middle ?? [];
            //     $shortHeadingMiddle = Str::limit(implode(', ', $headings), 50, '...');
            //     $fullHeadingMiddle = nl2br(e(implode('<br>', $headings)));

            //     return '
            //         <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
            //             View Heading Middle
            //         </button>
            //         <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $modalId . '-label" aria-hidden="true">
            //             <div class="modal-dialog modal-dialog-centered">
            //                 <div class="modal-content">
            //                     <div class="modal-header">
            //                         <h5 class="modal-title" id="' . $modalId . '-label">Heading Middle</h5>
            //                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            //                     </div>
            //                     <div class="modal-body">
            //                         ' . $fullHeadingMiddle . '
            //                     </div>
            //                     <div class="modal-footer">
            //                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            //                     </div>
            //                 </div>
            //             </div>
            //         </div>
            //     ';
            // })
            ->addColumn('action', function ($row) {
                $editBtn = '<button class="btn btn-primary btn-sm edit" data-row="' . base64_encode(json_encode($row)) . '">Edit</button>';
                $statusBtn = $row->{DonateModel::SLIDE_STATUS} === DonateModel::SLIDE_STATUS_DISABLED
                    ? '<button class="btn btn-success btn-sm" onclick="Enable(' . $row->id . ')">Enable</button>'
                    : '<button class="btn btn-danger btn-sm" onclick="Disable(' . $row->id . ')">Disable</button>';
                return $editBtn . ' ' . $statusBtn;
            })
            ->rawColumns(['heading_top', 'action'])
            ->make(true);
    } catch (Exception $exception) {
        return response()->json(['status' => false, 'message' => $exception->getMessage()]);
    }
}

    
    public function saveDonate(DonateRequest $request)
    {
        try {
            switch ($request->input("action")) {
                case "insert":
                    $return = $this->insertSlide($request);
                    break;
                case "update":
                    $return = $this->updateSlide($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableSlide($request);
                    break;
                default:
                    $return = ["status" => false, "message" => "Unknown action", "data" => ""];
            }
        } catch (Exception $exception) {
            $return = ["status" => false, "message" => $exception->getMessage(), "data" => ""];
        }
        return response()->json($return);
    }

    // public function insertSlide(DonateRequest $request)
    // {
    //     $imageUpload = $this->slideImageUpload($request);
    //     if ($imageUpload["status"]) {
    //         $donateModel = new DonateModel();
    //         $donateModel->{DonateModel::IMAGE} = $imageUpload["data"];
    //         $donateModel->{DonateModel::HEADING_TOP} = $request->input(DonateModel::HEADING_TOP);
    //         $donateModel->{DonateModel::HEADING_MIDDLE} = $request->input(DonateModel::HEADING_MIDDLE);
    //         $donateModel->{DonateModel::HEADING_BOTTOM} = $request->input(DonateModel::HEADING_BOTTOM);
    //         $donateModel->{DonateModel::SLIDE_STATUS} = $request->input(DonateModel::SLIDE_STATUS);
    //         $donateModel->{DonateModel::SLIDE_SORTING} = $request->input(DonateModel::SLIDE_SORTING);
    //         $donateModel->{DonateModel::STATUS} = 1;
    //         $donateModel->{DonateModel::CREATED_BY} = Auth::id();
    //         $donateModel->save();

    //         $this->forgetSlides();
    //         return ["status" => true, "message" => "Slide added successfully", "data" => null];
    //     }

    //     return $imageUpload;
    // }
    public function insertSlide(DonateRequest $request)
{
    // Upload image
    $imageUpload = $this->slideImageUpload($request);

    if ($imageUpload["status"]) {
        $donateModel = new DonateModel();

        // Set the image
        $donateModel->{DonateModel::IMAGE} = $imageUpload["data"];

        // // Encode arrays to JSON for heading_top and heading_middle
        // $donateModel->{DonateModel::HEADING_TOP} = json_encode($request->input(DonateModel::HEADING_TOP, []));
        // $donateModel->{DonateModel::HEADING_MIDDLE} = json_encode($request->input(DonateModel::HEADING_MIDDLE, []));

        // Set the rest of the fields
        $donateModel->{DonateModel::HEADING_TOP} = $request->input(DonateModel::HEADING_TOP);
        $donateModel->{DonateModel::SLIDE_STATUS} = $request->input(DonateModel::SLIDE_STATUS);
        $donateModel->{DonateModel::SLIDE_SORTING} = $request->input(DonateModel::SLIDE_SORTING);
        $donateModel->{DonateModel::STATUS} = 1;
        $donateModel->{DonateModel::CREATED_BY} = Auth::id();

        // Save the model
        $donateModel->save();

        // Clear cached slides
        $this->forgetSlides();

        return ["status" => true, "message" => "Slide added successfully", "data" => null];
    }

    return $imageUpload;
}


    public function slideImageUpload(DonateRequest $request)
    {
        $maxId = DonateModel::max(DonateModel::ID) + 1;
        $timestamp = strtotime(now());
        $filename = "slide_{$maxId}_{$timestamp}";
        return $this->uploadLocalFile($request, "image", "/website/uploads/Slider/", $filename);
    }

    public function updateSlide(DonateRequest $request)
    {
        $donateModel = DonateModel::where([
            DonateModel::ID => $request->input(DonateModel::ID),
            DonateModel::STATUS => 1
        ])->first();

        if ($donateModel) {
            if ($request->hasFile("image")) {
                $imageUpload = $this->slideImageUpload($request);
                if ($imageUpload["status"]) {
                    $donateModel->{DonateModel::IMAGE} = $imageUpload["data"];
                } else {
                    return $imageUpload;
                }
            }

            $donateModel->{DonateModel::HEADING_TOP} = $request->input(DonateModel::HEADING_TOP);
            // $donateModel->{DonateModel::HEADING_MIDDLE} = $request->input(DonateModel::HEADING_MIDDLE);
            // $donateModel->{DonateModel::HEADING_BOTTOM} = $request->input(DonateModel::HEADING_BOTTOM);
            $donateModel->{DonateModel::SLIDE_SORTING} = $request->input(DonateModel::SLIDE_SORTING);
            $donateModel->{DonateModel::SLIDE_STATUS} = $request->input(DonateModel::SLIDE_STATUS);
            $donateModel->{DonateModel::UPDATED_BY} = Auth::id();
            $donateModel->save();

            $this->forgetSlides();
            return ["status" => true, "message" => "Slide updated successfully", "data" => null];
        }

        return ["status" => false, "message" => "Slide not found", "data" => null];
    }

    public function enableDisableSlide(DonateRequest $request){
        $check = DonateModel::find($request->input(DonateModel::ID));
        if($check){
            $check->{DonateModel::UPDATED_BY} = Auth::user()->id;
            if($request->input("action")=="enable"){
                $check->{DonateModel::SLIDE_STATUS} = DonateModel::SLIDE_STATUS_LIVE;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{DonateModel::SLIDE_STATUS} = DonateModel::SLIDE_STATUS_DISABLED;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }
    // private function generateModalContent($row, $field, $title)
    // {
    //     $modalId = "modal-{$field}-{$row->id}";
    //     $shortText = \Illuminate\Support\Str::limit(strip_tags($row->{$field}), 50, '...');
    //     $fullText = nl2br(e($row->{$field}));

    //     return '
    //         <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
    //             View ' . $title . '
    //         </button>
    //         <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $modalId . '-label" aria-hidden="true">
    //             <div class="modal-dialog modal-dialog-centered">
    //                 <div class="modal-content">
    //                     <div class="modal-header">
    //                         <h5 class="modal-title" id="' . $modalId . '-label">' . $title . '</h5>
    //                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    //                     </div>
    //                     <div class="modal-body">' . $fullText . '</div>
    //                     <div class="modal-footer">
    //                         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    //                     </div>
    //                 </div>
    //             </div>
    //         </div>
    //     ';
    // }

    // private function generateActionButtons($row)
    // {
    //     $btnEdit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
    //     $btnStatus = $row->{DonateModel::SLIDE_STATUS} === DonateModel::SLIDE_STATUS_DISABLED
    //         ? '<a href="javascript:void(0)" onclick="Enable(' . $row->{DonateModel::ID} . ')" class="btn btn-primary btn-sm">Enable</a>'
    //         : '<a href="javascript:void(0)" onclick="Disable(' . $row->{DonateModel::ID} . ')" class="btn btn-danger btn-sm">Disable</a>';
    //     return $btnEdit . $btnStatus;
    // }
}
