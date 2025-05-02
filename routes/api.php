<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\NewsLetterController;
use App\Http\Controllers\BussConfrenceController;
use App\Http\Controllers\WebSiteElementsController;
use App\Http\Controllers\TeamInfoController;
use App\Http\Controllers\AboutUsController;
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
use App\Http\Controllers\WeddingController;
use App\Http\Controllers\SatsangController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|

*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('home-elements',[WebSiteElementsController::class,'homeElements']);
Route::get('get-blogs',[BlogController::class,'getBlogs']);
Route::get('blog-details/{id}',[BlogController::class,'blogDetails']);

// About Api 
Route::get('get-about',[AboutUsController::class,'getAbout']);
// Route::get('about-details/{id}',[AboutUsController::class,'aboutDetails']);

// team api
Route::get('get-team',[TeamInfoController::class,'getTeam']);
// Route::get('team-details/{id}',[TeamInfoController::class,'teamDetail']);


// facilities api 
Route::get('get-facilities',[FacilityController::class,'getFacility']);
Route::get('all-facilities',[FacilityController::class,'allFacility']);


// testimonial api
Route::get('get-testimonial',[TestimonialController::class,'testimonialData']);
Route::get('testimonial-details/{id}',[TestimonialController::class,'TestimonialDetails']);


Route::get('get-room',[RoomController::class,'getRoom']);
Route::get('room-details/{slug}',[RoomController::class,'roomDetail']);

// gallery api

Route::get('get-gallery',[GalleryController::class,'getGallery']);

// video gallery api
Route::get('get-video-gallery',[VideoGalleryController::class,'getVideoGallery']);
Route::get('get-guest-exp',[GuestExperienceController::class,'getGuestExp']);
// offer api
Route::get('get-offers-data',[OfferController::class,'offerApi']);

// category api 

Route::get('get-categories',[CategoryController::class,'getCategory']);

// packages details 
Route::get('get-packages',[PackagesController::class,'getPackages']);


// services
Route::get('get-services',[ServiceController::class,'getService']);

// wedding time line api  
Route::get('get-wed-time',[WedTimelineController::class,'allWedTimeline']);

// wedding venuse
Route::get('get-wed-venuses',[WedVenuseController::class,'allWedVenuse']);


Route::get('get-activity',[ActivityController::class,'getActivity']);
Route::get('get-wedding',[WeddingController::class,'getWedding']);
Route::get('get-satsang',[SatsangController::class,'getSatsang']);
Route::get('get-conference',[BussConfrenceController::class,'getConfrence']);

// subscribe news letter api  
Route::post('store-subscriber',[NewsLetterController::class,'subscribeNewsLetter']);

