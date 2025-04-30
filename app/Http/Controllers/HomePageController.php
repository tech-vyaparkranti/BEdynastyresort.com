<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Blog;
use App\Models\NavMenu;
use App\Models\DonateModel;
use App\Models\GalleryItem;
use App\Models\SliderModel;
use App\Models\AboutService;
use Illuminate\Http\Request;
use App\Models\OurGuestModel;
use App\Models\DownloadFile;
use App\Models\PackageMaster;
use App\Models\WebSiteElements;
use App\Traits\CommonFunctions;
use Mews\Captcha\Facades\Captcha;

class HomePageController extends Controller
{
    use CommonFunctions;
    
    public function homePage(){
        try{
            
            $getPackages = (new PackageMasterController())->getActivePackages();
            $packageCategory = (new PackageCategoryController())->getActivePackagesCategoryData();
            $packages = PackageMaster::where(PackageMaster::STATUS, "1")->get();
            $destinations = PackageMaster::distinct()->pluck('package_country')->toArray();
            $review=OurGuestModel::where('slide_status','live')->get();
            $ourServices = (new OurServicesModelController())->getOurServiceData();
            $sliders=SliderModel::where([[SliderModel::SLIDE_STATUS,SliderModel::SLIDE_STATUS_LIVE],
            [SliderModel::SLIDE_STATUS,1]])->orderBy(SliderModel::SLIDE_SORTING,"desc")->get();
            $donates=DonateModel::where('slide_status','live')->get();
            $data = $this->getElement();
            $galleryItems = GalleryItem::where(GalleryItem::STATUS, 1)
    ->whereNotNull(GalleryItem::FILTER_CATEGORY)
    ->where(GalleryItem::FILTER_CATEGORY, 'Daily Darshan')
    ->orderBy('position', 'desc')  // Change 'desc' to 'asc' for ascending order
    ->get();

    
            $blogs = Blog::where(Blog::BLOG_STATUS, Blog::BLOG_STATUS_LIVE)
                ->orderBy(Blog::BLOG_SORTING, 'desc')
                ->take(3)
                ->get();
            return view("HomePage.dynamicHomePage",compact('getPackages','packageCategory','packages','destinations','review','sliders','blogs','ourServices','donates','galleryItems','donates'),$data);
        }catch(Exception $exception){
            echo $exception->getMessage();
            return false;
        }
    }
    // public function dynamicHomePage(){
    //     return view("HomePage.dynamicHomePage");
    // }
    public function aboutUs(){
        $data = $this->getElement();
        // $ourServices = (new OurServicesModelController())->getOurServiceData();
        // $aboutServices = AboutService::where(AboutService::STATUS,'1')->get();
        $aboutServices = (new AboutServiceController())->getAboutServiceData();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.aboutUs",$data,compact('aboutServices','donates'));
    }
    public function termsConditions(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.termsConditions",$data,compact('donates'));
    }
    public function download(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.download",$data,compact('donates'));
    }
    public function shippingDeliverypolicy(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.shippingDeliverypolicy",$data,compact('donates'));
    }
    public function CancellationRefundPolicy(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.CancellationRefundPolicy",$data,compact('donates'));
    }
    public function privacyPolicy(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.privacyPolicy",$data,compact('donates'));
    }
    // public function ourServicesPage(){
        // return view("HomePage.ourServicesPage");
    // }
    public function servicePages(){
        $ourServices = (new OurServicesModelController())->getOurServiceData();
        $donates=DonateModel::where('slide_status','live')->get();
        $data = $this->getElement();
        return view("HomePage.servicePages",compact('ourServices','donates'),$data);
    }
    // public function galleryPages(){
    //     $data["galleryImages"] =$this->getCachedGalleryItems();
    //     $data[GalleryItem::FILTER_CATEGORY] = collect($data["galleryImages"])->unique(GalleryItem::FILTER_CATEGORY);
    //     return view("HomePage.galleryPages",$data);
    // }
    public function galleryPages() {
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        // Fetch gallery items with unique filter categories
        $galleryItems = GalleryItem::where(GalleryItem::STATUS, 1)
            ->whereNotNull(GalleryItem::FILTER_CATEGORY)
            ->get();
    
        // Extract unique filter categories
        $filterCategories = $galleryItems->unique(GalleryItem::FILTER_CATEGORY)->pluck(GalleryItem::FILTER_CATEGORY);
    
        // return view('HomePage.galleryPages', [
        //     'galleryImages' => $galleryItems,
        //     'filter_category' => $filterCategories,
        // ],compact('donates'),$data);
        return view('HomePage.galleryPages', array_merge(
            (array) $data,
            compact('donates'),
            [
                'galleryImages' => $galleryItems,
                'filter_category' => $filterCategories
            ]
        ));
        
    }
    
    public function contactUs(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        return view("HomePage.contactUs",$data,compact('donates'));
    }
    public function ComingSoon(){
        return view("HomePage.ComingSoon");
    }
    public function event(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        $getPackages = (new PackageMasterController())->getActivePackages();
        return view("HomePage.event",compact('getPackages','donates'),$data);
    }
    // public function servicePages(){
    //     return view("HomePage.servicePages");
    // }
    // public function tourpage(){
    //     $data = $this->getElement();
    //     return view("HomePage.tourpage");
    // }
    // public function tourDetailpage(){
    //     $data = $this->getElement();
    //     return view("HomePage.tourDetailpage");
    // }
    public function destinationpage(){
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        $getPackages = (new PackageMasterController())->getActivePackages();
        $packageCategory = (new PackageCategoryController())->getActivePackagesCategoryData();
        // $data = $this->getElement();
        $packages = PackageMaster::where(PackageMaster::STATUS, "1")->get();
        $destinations = PackageMaster::distinct()->pluck('package_country')->toArray();
        return view("HomePage.destinationpage", compact('destinations', 'packages', 'packageCategory', 'getPackages','donates'),$data);
    }
    public function destinationDetailpage($slug){
        $data = $this->getElement();
        $donates = DonateModel::where('slide_status', 'live')->get();
        
        // Fetch destination based on the provided slug
        $destination = PackageMaster::where('slug', $slug)->firstOrFail();
        
        // Fetch distinct package countries (you should fetch slugs instead of 'package_country')
        $destinations = PackageMaster::distinct()->pluck('slug')->toArray(); 
    
        // Fetch package details based on package country from the selected destination
        $package = PackageMaster::where(PackageMaster::STATUS, 1)
            ->where('slug', $destination->slug) // Use the slug instead of 'package_country'
            ->firstOrFail();
    
        // Fetch all packages for the selected destination's slug
        $packages = PackageMaster::where(PackageMaster::STATUS, 1)
            ->where('slug', $destination->slug) // Use the slug for the packages
            ->get();
    
        // Fetch countries excluding the selected destination's slug
        $country = PackageMaster::select('slug', 'package_image', 'package_name')
            ->where('status', 1)
            ->where('slug', '!=', $destination->slug) // Exclude the selected slug
            ->get();
        
        // Return the destination detail page with the relevant data
        return view("HomePage.destinationDetailpage", compact('package', 'destinations', 'destination', 'country', 'packages', 'donates'), $data);
    }
    
    public function blogpage(){
        $data = $this->getElement();
        $blogs = Blog::where(Blog::BLOG_STATUS, Blog::BLOG_STATUS_LIVE)
            ->orderBy(Blog::BLOG_SORTING, 'desc')
            ->get();
            $donates=DonateModel::where('slide_status','live')->get();
        $otherBlogs = Blog::where(Blog::BLOG_STATUS, Blog::BLOG_STATUS_LIVE)
            ->orderBy(Blog::BLOG_SORTING, 'desc')
            ->inRandomOrder()
            ->take(4)
            ->get();
        // $data = $this->getElement();
        $galleryItems = $this->getCachedGalleryItems(); // Ensure this returns a valid collection or query builder
        $galleryImages = $galleryItems->shuffle()->take(9); // Randomize and limit to 9 items


        $data['galleryImages'] = $galleryImages;
        return view("HomePage.blogpage", $data, compact('otherBlogs', 'blogs','donates'));
    }
    public function blogDetailpage($slug)
    {
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        $blogs = Blog::where('slug', $slug)
            ->where(Blog::BLOG_STATUS, Blog::BLOG_STATUS_LIVE)
            ->firstOrFail();
        $otherBlogs = Blog::where('slug', '!=', $slug)
            ->where(Blog::BLOG_STATUS, Blog::BLOG_STATUS_LIVE)
            ->orderBy(Blog::BLOG_SORTING, 'desc')
            ->take(4)
            ->get();
        $galleryItems = $this->getCachedGalleryItems(); // Ensure this returns a valid collection or query builder
        $galleryImages = $galleryItems->shuffle()->take(9); // Randomize and limit to 9 items

        // Prepare additional data for the view
        $data['galleryImages'] = $galleryImages;
        return view("HomePage.blogDetailpage", $data, compact('blogs', 'otherBlogs','donates'));
    }


    public function getMenu(){
        $menuItems = NavMenu::where([
            [NavMenu::STATUS,1],
            [NavMenu::VIEW_IN_LIST,NavMenu::VIEW_IN_LIST_YES]])
        ->select(NavMenu::TITLE,NavMenu::URL,NavMenu::URL_TARGET,NavMenu::PARENT_ID,
        NavMenu::NAV_TYPE,NavMenu::POSITION,NavMenu::ID)
        ->orderBy(NavMenu::PARENT_ID,"asc")
        ->orderBy(NavMenu::POSITION,"asc")->get();
        $returnData = [];
        if(count($menuItems)){
            // Nav Menu By Type
            $menuItemTypes = collect($menuItems)->groupBy(NavMenu::NAV_TYPE)->toArray();
             
            foreach($menuItemTypes as $navType=>$val){
                //for each type item
                foreach($val as $item){
                    if(!filter_var($item[NavMenu::URL], FILTER_VALIDATE_URL)){
                        $item[NavMenu::URL] = url("")."/".$item[NavMenu::URL];
                        //dd(url("items"));
                    }
                    //parent id is null
                    if($item[NavMenu::PARENT_ID]==null && !isset($returnData[$navType][$item[NavMenu::ID]])){
                        $returnData[$navType][$item[NavMenu::ID]] = $item;
                    }
                    //if parent id is set i.e child node
                    if($item[NavMenu::PARENT_ID]){
                        $this->setChildren($returnData,$item);
                    }
                }                 
            } 
            if(count($returnData)){
                $return = ["status"=>true,"message"=>"menu items found.","data"=>$returnData,
                "menu_html"=>$this->getHtml($returnData)];
            }else{
                $return = ["status"=>false,"message"=>"menu items not found.","data"=>null];
            }
        }else{
            $return = ["status"=>false,"message"=>"menu items not set","data"=>null];
        }
        return response()->json($return);
    }

    public function setChildren(&$returnData,$item){

        foreach($returnData as $navType=>$navItem){
            //parent id matches             
            if($navType==$item[NavMenu::NAV_TYPE] && !empty($navItem[$item[NavMenu::PARENT_ID]])){
                $returnData[$item[NavMenu::NAV_TYPE]][$item[NavMenu::PARENT_ID]]["child_node"][] = $item;
                return true;
            }
            if(!empty($returnData[$item[NavMenu::NAV_TYPE]])){
                
                $this->findSetChild($returnData[$item[NavMenu::NAV_TYPE]],$item);
            }             

        }

        
    }
    public function getElement()
    {
        $elements = $this->getWebSiteElements();
        $data = [];
        if (!empty($elements)) {
            foreach ($elements as $item) {
                $data[$item->{WebSiteElements::ELEMENT}] = $item->{WebSiteElements::ELEMENT_DETAILS};
            }
        }
        return $data;
    }
    
    /**
     * findSetChild
     *
     * @param  mixed $item
     * @param  mixed $itemSet
     * @return void
     */
    public function findSetChild(&$item,$itemSet){
        try{
            foreach($item as $navId=>$navItem){
                if($navItem[NavMenu::ID]==$itemSet[NavMenu::PARENT_ID]){
                    $item[$navId]["child_node"][] = $itemSet;
                    return true; 
                }
                if(!empty($item[$navId]["child_node"])){
                    return $this->findSetChild($item[$navId]["child_node"],$itemSet);
                }
            }
        }catch(Exception $exception){
            return false;
        }
    }
    
    /**
     * getHtml
     *
     * @param  mixed $returnData
     * @return void
     */
    public function getHtml($returnData){
        $html = [];
        foreach($returnData as $key=>$value){
            if(!isset($html[$key])){
                $html[$key] = '';
            }
            foreach($value as $keyVal){
                $html[$key] .= $this->getItemHTML($key,$html,$keyVal);
            }
            //$html[$key] = $this->getItemHTML($key,$html,$value);
        }
        return $html;
    }
    
    /**
     * getItemHTML
     *
     * @param  mixed $key
     * @param  mixed $html
     * @param  mixed $item
     * @return void
     */
    public function getItemHTML($key,$html,$item){
         
        $link_html = "";
        if($key=="top"){
            if(!empty($item["child_node"])){
                
                $subMenu = $this->getItemChildHTML($item,'<div class="dropdown-menu">');   
                $link_html .= '<li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                                        '.$item[NavMenu::TITLE].'
                                    </a>'.$subMenu.'</div>
                                    </li>';
            }else{
                $link_html .= '<li class="nav-item">
                                    <a target="'.$item[NavMenu::URL_TARGET].'" class="nav-link js-scroll-trigger" href="'.
                                    $item[NavMenu::URL].'">'.$item[NavMenu::TITLE].'</a>
                               </li>';
            }
        }
        
        return $link_html;  
    }
    
    /**
     * getItemChildHTML
     *
     * @param  mixed $item
     * @param  mixed $html
     * @return void
     */
    public function getItemChildHTML($item,$html){
        if(!empty($item["child_node"])){
            $html .='<a target="'.$item[NavMenu::URL_TARGET].'" class="dropdown-item" href="'.$item[NavMenu::URL].'">'.$item[NavMenu::TITLE].'</a>';
            foreach($item["child_node"] as $item_new){
                return $this->getItemChildHTML($item_new,$html);
            }            
        }else{
            return $html .='<a target="'.$item[NavMenu::URL_TARGET].'" class="dropdown-item" href="'.$item[NavMenu::URL].'">'.$item[NavMenu::TITLE].'</a>';
        }
    }
     
    public function galleryPage(){
        $obj = new GalleryItem();
        $getAllGalleryImages = $obj->getAllGalleryImages();
        $getAllVideos = $obj->getAllGalleryVideos();
        return view("HomePage.galleryPage",compact("getAllGalleryImages","getAllVideos"));
    }

    public function refreshCapthca(){
        try{
            $return = ["status"=>true,"message"=>"Success","data"=>Captcha::src()];
            
        }catch(Exception $exception){
            $return = ["status"=>false,"message"=>$exception->getMessage(),"data"=>$exception->getTraceAsString()];
        }
        return response()->json($return);
    }
    public function getDownload()
    {
        $data = $this->getElement();
        $donates=DonateModel::where('slide_status','live')->get();
        $downloadFile = DownloadFile::where('status',"enable")->get();
        return view('HomePage.download',$data ,compact('downloadFile','donates'));
    }

    public function downloadFile($id)
    {
        $fileRecord = DownloadFile::findOrFail($id);
        $flePath = public_path($fileRecord->local_file);

        if(file_exists($flePath))
        {
            return response()->download($flePath);
        }
        else{
            return redirect()->back()->with("error","file not found");
        }
    }
}
