<?php
namespace App\Traits;

use Exception;
use Carbon\Carbon;
use App\Models\GalleryItem;
use App\Mail\ContactUsEMail;
use App\Models\ContactUsModel;
use App\Models\WebSiteElements;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait CommonFunctions{

    public function reportException(Exception $exception){
        dd([
            "message"=>$exception->getMessage(),
            "File"=>$exception->getFile(),
            "Code"=>$exception->getCode(),
            "Trace as string"=>$exception->getTraceAsString()
        ]);
    }



    public function uploadLocalFile(FormRequest $fileObject,$key_name,$upload_path,$file_name = "",int $height = null,int $width = null):array{
        try{
            $uploadFile = $fileObject->file($key_name);
             
            $fileName = $uploadFile->getClientOriginalName();

            $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
            $fileExtension = $uploadFile->extension();
            $timeString = strtotime($this->timeNow());
            $fileName = "file_$timeString".$file_name.preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
            
            $fileUploaded = $uploadFile->move(public_path().$upload_path, $fileName);
            $path = $upload_path.$fileName;
            $data = config('app.url').$path;
            // $data = url($path);
            if($height && $width){
                $newName = $upload_path.$fileNameWithoutExtension."img_$timeString"."_cropped.$fileExtension";
                $resizeImage = Image::make(public_path().$upload_path.$fileName);
                 
                $resizeImage->resize($width,$height,function($constraint){
                    $constraint->aspectRatio();
                })->crop($width,$height)->save(public_path().$newName,100,$fileExtension);
                if(file_exists(public_path().$newName)){
                    $data = $newName;
                }
            }
            if($fileUploaded){
                $return = ["status"=>true,"message"=>"Success","data"=>$data];
            }else{
                $return = ["status"=>false,"message"=>"failed","data"=>$fileUploaded];
            }
        }catch(Exception $exception){
            $this->reportException($exception);
            $return = ["status"=>false,"message"=>$exception->getMessage(),"data"=>$exception->getMessage()];
        } 
        return $return;
    }

    public function uploadMultipleLocalFiles(FormRequest $fileObject, string $key_name, string $upload_path, string $file_name_suffix = "", int $height = null, int $width = null): array
    {
        $results = [];

        try {
            $uploadFiles = $fileObject->file($key_name);

            foreach ($uploadFiles as $uploadFile) {
                $fileName = $uploadFile->getClientOriginalName();
                $fileNameWithoutExtension = pathinfo($fileName, PATHINFO_FILENAME);
                $fileExtension = $uploadFile->extension();
                $timeString = strtotime($this->timeNow());

                $generatedFileName = "file_{$timeString}_{$file_name_suffix}_" . preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
                $fullUploadPath = public_path() . $upload_path;

                // Ensure directory exists
                if (!file_exists($fullUploadPath)) {
                    mkdir($fullUploadPath, 0755, true);
                }

                $fileUploaded = $uploadFile->move($fullUploadPath, $generatedFileName);
                $fullFilePath = $upload_path . $generatedFileName;
                $fileUrl = config('app.url') . $fullFilePath;

                // Optional resize/crop
                if ($height && $width) {
                    $newName = $upload_path . $fileNameWithoutExtension . "_img_{$timeString}_cropped.{$fileExtension}";

                    $resizeImage = Image::make(public_path() . $fullFilePath);
                    $resizeImage->resize($width, $height, function ($constraint) {
                        $constraint->aspectRatio();
                    })->crop($width, $height)->save(public_path() . $newName, 100, $fileExtension);

                    if (file_exists(public_path() . $newName)) {
                        $fileUrl = config('app.url') . $newName;
                    }
                }

                $results[] = [
                    "status" => $fileUploaded ? true : false,
                    "message" => $fileUploaded ? "Success" : "Failed",
                    "data" => $fileUrl
                ];
            }

        } catch (Exception $exception) {
            $this->reportException($exception);

            return [[
                "status" => false,
                "message" => $exception->getMessage(),
                "data" => null
            ]];
        }

        return $results;
    }


    public function timeNow(){
        return Carbon::now();
    }
    public function returnMessage($message,$status=false,$data = []){
        return ["status"=>$status,"message"=>$message,"data"=>$data];
    }

    public function getCachedGalleryItems(){
        return Cache::rememberForever('galleryImages', function () {
            return GalleryItem::where([
                [GalleryItem::STATUS,1],
                [GalleryItem::VIEW_STATUS,GalleryItem::VIEW_STATUS_VISIBLE]
            ])->select(GalleryItem::LOCAL_IMAGE,
            GalleryItem::IMAGE_LINK,GalleryItem::ALTERNATE_TEXT,GalleryItem::TITLE,
            GalleryItem::FILTER_CATEGORY)
            // ->whereNULL(GalleryItem::VIDEO_LINK)
            ->whereNULL(GalleryItem::IMAGE_LINK)->orderBy(GalleryItem::POSITION,'asc')->get();
        });
    }

    public function sendContactUsEmail(ContactUsModel $contactUsModel){
        try{
            Mail::to("travel@grstic.com")
            ->cc(["grstic.com@gmail.com"])->send(new ContactUsEMail,
            $contactUsModel->toArray());
        }catch(Exception $exception){
            report($exception);
        }
    }
    public function getIp(){
        foreach (array('REMOTE_ADDR','HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
    }

    public function addDiv($item,$class="row",$id=""){
        return '<div class="'.$class.'" id="'.$id.'">'.$item.'</div>';
    }
    public function forgetSlides(){
        Cache::forget('slides');
    }
    public function getModal(int $id,string $data,$buttontitle,$modalTitle = ""){
        return '
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal'.$id.'">
  '.$buttontitle.'
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal'.$id.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">'.$modalTitle.'</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        '.$data.'
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>';
    }
    public function forgetWebSiteElements(){
        Cache::forget('webSiteElements');
    }
    public function getWebSiteElements(){
        return Cache::rememberForever('webSiteElements', function () {
            return WebSiteElements::where(WebSiteElements::STATUS,1)->get();
        });
    }
    
}