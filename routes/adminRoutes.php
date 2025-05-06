<?php

use App\Http\Controllers\AboutServiceController;
use App\Models\PackageMaster;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\OurGuestController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\EnquiryFormController;
use App\Http\Controllers\DownloadFileController;
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\WebSiteElementsController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\TeamInfoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\VideoGalleryController;
use App\Http\Controllers\GuestExperienceController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\WedTimelineController;
use App\Http\Controllers\WedVenuseController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SatsangController;
use App\Http\Controllers\BussConfrenceController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\WeddingEnquiryController;



Route::get("login",[AdminController::class,"Login"])->name("login");
Route::post("AdminUserLogin",[AdminController::class,"AdminLoginUser"])->name("AdminLogin");
Route::get("getmenu-items",[HomePageController::class,"getMenu"]);
//pages

Route::middleware(['auth'])->group(function () {
    Route::get("/new-dashboard",[AdminController::class,"dashboard"])->name("new-dashboard");
    
    Route::get("manage-gallery",[GalleryController::class,"viewGallery"])->name("manageGallery");
    Route::post("addGalleryItems",[GalleryController::class,"saveGallery"])->name("saveGallery");
    Route::post("addGalleryDataTable",[GalleryController::class,"galleryData"])->name("galleryData");

 
  Route::match(['get', 'post'], 'logout',  [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    //package Master
    

    //contactUsData
    Route::get("contact-us-admin-page", [ContactUsController::class, "contactUsAdminPage"])->name("contactUsData");
    Route::post("contact-us-data-table", [ContactUsController::class, "contactUsDataTable"])->name("contactUsDataTable");

    //enquiryData
    Route::get("enquiry-admin-page", [EnquiryFormController::class, "enquiryAdminPage"])->name("enquiryAdminPage");
    Route::post("enquiry-data-table", [EnquiryFormController::class, "enquiryDataTable"])->name("enquiryDataTable");

    Route::get("blog-admin", [BlogController::class, "blogSlider"])->name("blogSlider");
    Route::post("save-blog", [BlogController::class, "saveBlog"])->name("saveBlog");
    Route::post("blog-data", [BlogController::class, "blogData"])->name("blogData");

    Route::get("add-web-site-elements", [WebSiteElementsController::class, "addWebSiteElements"])->name("webSiteElements");
    Route::post("save-web-site-element", [WebSiteElementsController::class, "saveWebSiteElement"])->name("saveWebSiteElement");
    Route::post("web-site-elements-data", [WebSiteElementsController::class, "getWebElementsData"])->name("webSiteElementsData");

    Route::get("view-team-info", [TeamInfoController::class, "viewTeam"])->name("viewTeamInfo");
    Route::post("save-team-info", [TeamInfoController::class, "saveTeamInfo"])->name("saveTeamInfo");
    Route::post("get-team-info", [TeamInfoController::class, "getTeamInfo"])->name("getTeamInfo");


    Route::get("view-about-info", [AboutUsController::class, "viewAboutUs"])->name("viewAboutInfo");
    Route::post("save-about-info", [AboutUsController::class, "saveAboutUs"])->name("saveAbout");
    Route::post("get-about-info", [AboutUsController::class, "aboutData"])->name("getAboutData");


    Route::get("view-facility", [FacilityController::class, "viewFacility"])->name("viewFacility");
    Route::post("save-facility", [FacilityController::class, "saveFacility"])->name("saveFacility");
    Route::post("get-facility", [FacilityController::class, "facilityData"])->name("facilityData");

    Route::get("view-testimonial", [TestimonialController::class, "viewTestimonial"])->name("viewTestimonial");
    Route::post("save-testimonial", [TestimonialController::class, "saveTestimonial"])->name("saveTestimonial");
    Route::post("get-testimonial", [TestimonialController::class, "getTestimonial"])->name("getTestimonial");

    Route::get("view-room", [RoomController::class, "viewRoom"])->name("viewRoom");
    Route::post("save-room", [RoomController::class, "saveRoom"])->name("saveRoom");
    Route::post("get-room-data", [RoomController::class, "roomData"])->name("roomData");

    Route::get("manage-video-gallery",[VideoGalleryController::class,"viewVideo"])->name("viewVideo");
    Route::post("save-video-gallery",[VideoGalleryController::class,"saveVideoGallery"])->name("saveVideoGallery");
    Route::post("get-video-data",[VideoGalleryController::class,"videoData"])->name("videoData");

    Route::get("manage-guest-exp",[GuestExperienceController::class,"viewGuestExp"])->name("viewGuestExp");
    Route::post("save-guest-exp",[GuestExperienceController::class,"saveGuestExp"])->name("saveGuestExp");
    Route::post("guest-exp-data",[GuestExperienceController::class,"guestExpData"])->name("guestExpData");


    Route::get("manage-offer",[OfferController::class,"viewOffer"])->name("viewOffer");
    Route::post("save-offer",[OfferController::class,"saveOffer"])->name("saveOffer");
    Route::post("offer-data",[OfferController::class,"offerData"])->name("offerData");

    Route::get("manage-category",[CategoryController::class,"viewCategory"])->name("viewCategory");
    Route::post("save-category",[CategoryController::class,"saveCategory"])->name("saveCategory");
    Route::post("get-category",[CategoryController::class,"categoryData"])->name("categoryData");

    Route::get("manage-packages",[PackagesController::class,"viewPackages"])->name("viewPackages");
    Route::post("save-packages",[PackagesController::class,"savePackages"])->name("savePackages");
    Route::post("packages-data",[PackagesController::class,"packageData"])->name("packageData");

    Route::get("manage-service",[ServiceController::class,"viewService"])->name("viewService");
    Route::post("save-service",[ServiceController::class,"saveService"])->name("saveService");
    Route::post("service-data",[ServiceController::class,"serviceData"])->name("serviceData");

    Route::get("manage-wed-timeline",[WedTimelineController::class,"viewWedTimeline"])->name("viewWedTimeline");
    Route::post("save-wed-timeline",[WedTimelineController::class,"saveWedTimeline"])->name("saveWedTimeline");
    Route::post("wed-timeline-data",[WedTimelineController::class,"wedTimelineData"])->name("wedTimelineData");

    Route::get("manage-wed-venuses",[WedVenuseController::class,"viewWedVenuse"])->name("viewWedVenuse");
    Route::post("save-wed-venuses",[WedVenuseController::class,"saveWedVenuse"])->name("saveWedVenuse");
    Route::post("wed-venuses-data",[WedVenuseController::class,"wedVenuseData"])->name("wedVenuseData");

    Route::get("manage-activity",[ActivityController::class,"viewActivity"])->name("viewActivity");
    Route::post("save-activity",[ActivityController::class,"saveActivity"])->name("saveActivity");
    Route::post("activity-data",[ActivityController::class,"activityData"])->name("activityData");

    Route::get("manage-satsang",[SatsangController::class,"viewSatsang"])->name("viewSatsang");
    Route::post("save-satsang",[SatsangController::class,"saveSatsang"])->name("saveSatsang");
    Route::post("satsang-data",[SatsangController::class,"satsangData"])->name("satsangData");

    Route::get("manage-wedding",[WeddingController::class,"viewWedding"])->name("viewWedding");
    Route::post("save-wedding",[WeddingController::class,"saveWedding"])->name("saveWedding");
    Route::post("wedding-data",[WeddingController::class,"weddingData"])->name("weddingData");

    Route::get("manage-conference",[BussConfrenceController::class,"viewConfrence"])->name("viewConfrence");
    Route::post("save-conference",[BussConfrenceController::class,"saveConfrence"])->name("saveConfrence");
    Route::post("conference-data",[BussConfrenceController::class,"confrenceData"])->name("confrenceData");

    Route::get("manage-slider",[SliderController::class,"slider"])->name("slider");
    Route::post("save-slider",[SliderController::class,"saveSlide"])->name("saveSlide");
    Route::post("slider-data",[SliderController::class,"sliderData"])->name("sliderData");

    Route::get("show-faq", [FAQController::class, "viewFaq"])->name("viewFaq");
    Route::post("save-faq", [FAQController::class, "saveFaq"])->name("saveFaq");
    Route::post("get-faq", [FAQController::class, "getFaq"])->name("getFaq");

    Route::get("show-subscribe", [NewsLetterController::class, "manageNewsLetterAdmin"])->name("viewNewsLetter");
    Route::post("subscribe-data", [NewsLetterController::class, "getNewsLetterData"])->name("getNewsData");  

    Route::get("manage-wed-enquiry", [WeddingEnquiryController::class, "wedEnquiry"])->name("wedEnquiry");
    Route::post("wed-enquiry-data", [WeddingEnquiryController::class, "enquiryDataTable"])->name("wedEnquiryTable");    

});


