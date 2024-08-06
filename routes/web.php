<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\Admin\TempImagesController;
use App\Http\Controllers\admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('admin.dashboard.index');
});
 
Route::group(['prefix'=>'admin'],function()
{
      Route::group(['middeleware=>admin.guest'],function(){

        Route::get('/login',[AdminLoginController::class,'index'])->name('admin.login'); 
        Route::post('/authenticate',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');        
      
    });
      Route::group(['middeleware=>admin.auth'],function(){
        
        Route::get('/dashboard',[HomeController::class,'index'])->name('admin.dashboard');
        Route::get('/logout',[HomeController::class,'logout'])->name('admin.logout');  
              
        //Category Routes
        Route::get('/categories',[CategoryController::class,'index'])->name('categories.index'); 
        Route::get('/categories/create',[CategoryController::class,'create'])->name('categories.create'); 
        Route::post('/categories',[CategoryController::class,'store'])->name('categories.store');   
        Route::get('/categories/{category}/edit',[CategoryController::class,'edit'])->name('categories.edit'); 
        Route::put('/categories/{category}',[CategoryController::class,'update'])->name('categories.update'); 
        Route::delete('/categories/{category}',[CategoryController::class,'destroy'])->name('categories.delete'); 
        
        //Sub Category Routes
        Route::get('/sub-categories',[SubCategoryController::class,'index'])->name('sub-categories.index'); 
        Route::get('/sub-categories/create',[SubCategoryController::class,'create'])->name('sub-categories.create'); 
        Route::post('/sub-categories',[SubCategoryController::class,'store'])->name('sub-categories.store');  
        Route::get('/sub-categories/{subCategory}/edit',[SubCategoryController::class,'edit'])->name('sub-categories.edit');  
        Route::put('/sub-categories/{subCategory}',[SubCategoryController::class,'update'])->name('sub-categories.update'); 
        Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class,'destroy'])->name('sub-categories.delete'); 
         
        //Product Routes
        Route::get('/products',[ProductController::class,'index'])->name('products.index'); 
        Route::get('/products/create',[ProductController::class,'create'])->name('products.create'); 
        Route::post('/products',[ProductController::class,'store'])->name('products.store');  
        Route::get('/products/{product}/edit',[ProductController::class,'edit'])->name('products.edit');
        Route::delete('/products/{product}',[ProductController::class,'destroy'])->name('products.delete');  
        
        //User Routes
        Route::get('/users',[UserController::class,'index'])->name('users.index');
        Route::get('/users/create',[UserController::class,'create'])->name('users.create');
        Route::post('/users',[UserController::class,'store'])->name('users.store');   
        Route::get('/users/{user}/edit',[UserController::class,'edit'])->name('users.edit');
        Route::put('/users/{user}',[UserController::class,'update'])->name('users.update');
        Route::delete('/users/{user}',[UserController::class,'destroy'])->name('users.delete');    

        Route::get('/product-subcategories',[ProductSubCategoryController::class,'index'])->name('product-subcategories.index');  
        //temp-images.create
         Route::post('/upload-temp-image',[TempImagesController::class,'create'])->name('temp-images.create');    
         // Order Routes
         Route::get('/orders',[OrderController::class,'index'])->name('orders.index');
         Route::get('/orders{id}',[OrderController::class,'detail'])->name('orders.detail');
         Route::post('/orders/change-status{id}',[OrderController::class,'changeOrderStatus'])->name('orders.changeOrderStatus');
         Route::get('/getSlug',function(Request $request){
     if(!empty($request->title))  
     {
       $slug= Str::slug($request->title);
     }
     return response()->json([
      'status'=>true,
      'slug'=>$slug
     ]);
        })->name('getSlug');
        
      });
});