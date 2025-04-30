<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Traits\CommonFunctions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    use CommonFunctions;
    //

    public function blogSlider(){
        return view("Dashboard.Pages.manageBlog");
    }

    public function blogData(){
        try{
            $query = Blog::select(Blog::IMAGE,
        Blog::ID,
        Blog::TITLE,
        Blog::CONTENT,
        Blog::BLOG_DATE,
        Blog::BLOG_CATEGORY,
        Blog::FACEBOOK_LINK,
        Blog::YOUTUBE_LINK,
        Blog::TWITTER_LINK,
        Blog::BLOG_IMAGES,
        Blog::INSTAGRAM_LINK,
        Blog::META_KEYWORD,
        Blog::META_TITLE,
        Blog::META_DESCRIPTION,
        Blog::BLOG_SORTING,
        Blog::BLOG_STATUS);
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row){
                $btn_edit = '<a data-row="' . base64_encode(json_encode($row)) . '" href="javascript:void(0)" class="edit btn btn-primary btn-sm">Edit</a>';
                
                $btn_disable = ' <a   href="javascript:void(0)" onclick="Disable('.$row->{Blog::ID}.')" class="btn btn-danger btn-sm">Disable</a>';
                $btn_enable = ' <a   href="javascript:void(0)" onclick="Enable('.$row->{Blog::ID}.')" class="btn btn-primary btn-sm">Enable </a>';
                if($row->{Blog::BLOG_STATUS}==Blog::BLOG_STATUS_DISABLED){
                    return $btn_edit.$btn_enable;
                }else{
                    return $btn_edit.$btn_disable;
                }
            })->addColumn(Blog::CONTENT."_row",function ($row){
                return $this->getModal($row->{Blog::ID},$row->{Blog::CONTENT}??" ",'View Blog Details',$row->{Blog::TITLE}??"Blog Title");
                })
            ->rawColumns(['action',Blog::CONTENT,Blog::CONTENT."_row"])
            ->make(true);
        }
        catch(Exception $except){
            dd([$except->getMessage()]);
        }
    }

    public function saveBlog(BlogRequest $request){
        try{
            switch($request->input("action")){
                case "insert":
                    $return = $this->insertBlog($request);
                    break;
                case "update":
                    $return = $this->updateBlog($request);
                    break;
                case "enable":
                case "disable":
                    $return = $this->enableDisableBlog($request);
                    break;
                default:
                $return = ["status"=>false,"message"=>"Unknown case","data"=>""];
            }
        }catch(Exception $exception){
            $return = $this->reportException($exception);
        }
        return response()->json($return);
    }

    public function insertBlog(BlogRequest $request){ 
        $imageUpload = $this->blogImageUpload($request);
        
        $blogImages = $this->blogMultipleImage($request);
        $blogImageUrls = array_column($blogImages, 'data');
        if($imageUpload['status'] || $blogImages['status'])
        {
            $Blog = new Blog();
            $Blog->{Blog::IMAGE} = $imageUpload['data'];
            $Blog->{Blog::BLOG_IMAGES} = json_encode($blogImageUrls);
            $Blog->{Blog::TITLE} = $request->input(Blog::TITLE);
            $Blog->{Blog::CONTENT} = $request->input(Blog::CONTENT);
            $Blog->{Blog::BLOG_DATE} = $request->input(Blog::BLOG_DATE);
            $Blog->{Blog::BLOG_CATEGORY} = $request->input(Blog::BLOG_CATEGORY);
            $Blog->{Blog::META_KEYWORD} = $request->input(Blog::META_KEYWORD);
            $Blog->{Blog::META_TITLE} = $request->input(Blog::META_TITLE);
            $Blog->{Blog::META_DESCRIPTION} = $request->input(Blog::META_DESCRIPTION);
            $Blog->{Blog::BLOG_STATUS} = $request->input(Blog::BLOG_STATUS);           
            $Blog->{Blog::STATUS} = 1;
            $Blog->{Blog::CREATED_BY} = Auth::user()->id;
            $Blog->save();
            $return = ["status"=>true,"message"=>"Saved successfully","data"=>null];
            $this->forgetSlides();
        }else{
            $return = $imageUpload;
        }
        return $return;
    }

    public function blogImageUpload(BlogRequest $request){
        $maxId = Blog::max(Blog::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadLocalFile($request,"image","/images/blog/","slide_$maxId");
    }

    public function blogMultipleImage(BlogRequest $request){
        $maxId = Blog::max(Blog::ID);
        $maxId += 1;
        $timeNow = strtotime($this->timeNow());
        $maxId .= "_$timeNow";
        return $this->uploadMultipleLocalFiles($request,"blog_images","/images/blogImages/","slide_$maxId");
    }

    public function updateBlog(BlogRequest $request){
        $check = Blog::where([Blog::ID=>$request->input(Blog::ID),Blog::STATUS=>1])->first();
        if($check){
            if($request->hasFile('image') ){
                $imageUpload =$this->blogImageUpload($request);
                $blogImages = $this->blogMultipleImage($request);
                $blogImageUrls = array_column($blogImages, 'data');
                if($imageUpload["status"]){
                    $check->{Blog::IMAGE} = $imageUpload["data"];
                    $check->{Blog::BLOG_IMAGES} = json_encode($blogImageUrls);
                    $check->{Blog::BLOG_SORTING} = $request->input(Blog::BLOG_SORTING);
                    $check->{Blog::TITLE} = $request->input(Blog::TITLE);
                    $check->{Blog::CONTENT} = $request->input(Blog::CONTENT);
                    $check->{Blog::BLOG_DATE} = $request->input(Blog::BLOG_DATE);
                    $check->{Blog::BLOG_CATEGORY} = $request->input(Blog::BLOG_CATEGORY);
                    $check->{Blog::META_KEYWORD} = $request->input(Blog::META_KEYWORD);
                    $check->{Blog::META_TITLE} = $request->input(Blog::META_TITLE);
                    $check->{Blog::META_DESCRIPTION} = $request->input(Blog::META_DESCRIPTION);
                    
                    $check->{Blog::BLOG_STATUS} = $request->input(Blog::BLOG_STATUS);
                    $check->{Blog::UPDATED_BY} = Auth::user()->id;
                    $check->save();
                    $this->forgetSlides();
                    $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];
                }else{
                    $return = $imageUpload;
                }
            }else{
                $check->{Blog::TITLE} = $request->input(Blog::TITLE);
                $check->{Blog::BLOG_SORTING} = $request->input(Blog::BLOG_SORTING);
                $check->{Blog::CONTENT} = $request->input(Blog::CONTENT);
                $check->{Blog::BLOG_DATE} = $request->input(Blog::BLOG_DATE);
                $check->{Blog::BLOG_CATEGORY} = $request->input(Blog::BLOG_CATEGORY);
                $check->{Blog::META_KEYWORD} = $request->input(Blog::META_KEYWORD);
                $check->{Blog::META_TITLE} = $request->input(Blog::META_TITLE);
                $check->{Blog::META_DESCRIPTION} = $request->input(Blog::META_DESCRIPTION);
                $check->{Blog::FACEBOOK_LINK} = $request->input(Blog::FACEBOOK_LINK);
                $check->{Blog::YOUTUBE_LINK} = $request->input(Blog::YOUTUBE_LINK);
                $check->{Blog::TWITTER_LINK} = $request->input(Blog::TWITTER_LINK);
                $check->{Blog::INSTAGRAM_LINK} = $request->input(Blog::INSTAGRAM_LINK);
                $check->{Blog::BLOG_STATUS} = $request->input(Blog::BLOG_STATUS);
                $check->{Blog::UPDATED_BY} = Auth::user()->id;
                $check->save();
                $this->forgetSlides();
                $return = ["status"=>true,"message"=>"Updated successfully","data"=>null];            
            }
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>null];
        }
        return $return;
    }

    public function enableDisableBlog(BlogRequest $request){
        $check = Blog::find($request->input(Blog::ID));
        if($check){
            $check->{Blog::UPDATED_BY} = Auth::user()->id;
            if($request->input("action")=="enable"){
                $check->{Blog::BLOG_STATUS} = Blog::BLOG_STATUS_LIVE;
                $return = ["status"=>true,"message"=>"Enabled successfully.","data"=>""];
            }else{
                $check->{Blog::BLOG_STATUS} = Blog::BLOG_STATUS_DISABLED;
                $return = ["status"=>true,"message"=>"Disabled successfully.","data"=>""];
            }
            $this->forgetSlides();
            $check->save();
        }else{
            $return = ["status"=>false,"message"=>"Details not found.","data"=>""];
        }
        return $return;
    }

    public function getBlogs()
    {
        $blogs = Blog::where('status',1)->get();
        $data = [
            'status' => true,
            'success' => true,
            'blogs' => $blogs,
        ];

        return response()->json($data, 200);
    }

    public function blogDetails($id)
    {
        $blog = Blog::where('id',$id)->first();
        $data = [
            'status' => true,
            'success' => true,
            'blog' => $blog,
        ];

        return response()->json($data, 200);
    }
}
