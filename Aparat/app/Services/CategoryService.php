<?php

namespace App\Services;

 use App\Http\Requests\Category\CreateCategoryRequest;
 use App\Http\Requests\Category\ListCategoryRequest;
 use App\Http\Requests\Category\UploadCategoryBannerRequest;
 use App\Models\Category;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Log;
 use Illuminate\Support\Facades\Storage;
 use Illuminate\Support\Str;
 use Lcobucci\JWT\Exception;


 class CategoryService extends BaseService
{


     public static function getAllCategories(ListCategoryRequest $request)
     {
         $categories = Category::all();
         return $categories;
     }

     public static function getMyCategories(ListCategoryRequest $request)
     {
         return auth()->user()->categories;

     }

     public static function create(CreateCategoryRequest $request)
     {

         try {
             DB::beginTransaction();
             $data = $request->validated();
             $user = auth()->user();

             if ($request->banner_id){
                 $bannerPath = auth()->id() . '/' . $request->banner_id;
                 Storage::disk('category')->move('tmp/' .$request->banner_id,$bannerPath);
             }

             $category = $user->categories()->create($data);

             DB::commit();
             return response(['data' => $category],200);

         }
         catch (Exception $exception){
             Log::error($exception);
             return response(['message' => 'خطایی رخ داده است'],500);

         }
     }

     public static function uploadBanner(UploadCategoryBannerRequest $request)
     {
         try {
             $banner=$request->file('banner');
             $fileName= time() . Str::random(10) . '-banner';
             Storage::disk('category')->put('/tmp/' . $fileName,$banner->get());


             return response([
                 'banner' => $fileName
             ],200);
         }

         catch (Exception $exception){

             return response([
                 'message'=>'خطایی رخ داده است'
             ],500);
         }     }


 }
