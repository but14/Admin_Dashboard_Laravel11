<?php

namespace App\Http\Controllers\admin;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use  App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;   

class ProductController extends Controller
{



    public function index(Request $request)
    {
        $products =Product::latest('id');
        if($request->get('keyword') != ""){
            $products =$products->where('title','like','%'.$request->keyword.'%');
        }
        $products =$products->paginate();
        $data['products'] = $products;
       return view('admin.products.list',$data);
    }
    public function create()
    {
        $data = [];
        $categories =Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        return view('admin.products.create',$data);
    }
    public function store(Request $request){
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'track_qty' => 'required|in:Yes,No'
        ];
        
        if(!empty($request->track_qty) && $request->track_qty =='Yes'){
            $rules['qty'] = 'required|numeric';
        }
        $validator =Validator::make($request->all(),$rules);
        if($validator->passes()){
           $product =new Product;
           $product->title =$request->title;
           $product->slug =$request->slug;
           $product->description =$request->description;
           $product->price =$request->price;
           $product->category_id =$request->category;
           $product->sub_category_id =$request->sub_category;
           $product->is_featured =$request->is_featured;
           $product->qty=$request->qty;
           $product->status=$request->status;
           $product->track_qty=$request->track_qty;
            $product->save();   

          //Save Gallery pics
          if(!empty($request->image_array)){
            foreach($request->image_array as $temp_image_id){
                $tempImageInfo = TempImage::find($temp_image_id);
                $extArray = explode('.',$tempImageInfo->name);
                $ext = last($extArray);
                
                $imageName = $product->id .'-'.time().'.'.$ext;
                
                $sPath = public_path().'/temp/'.$tempImageInfo->name;
                $dPath = public_path().'/uploads/product/'.$imageName;
                
                File::copy($sPath, $dPath);
                
                $product->image = $imageName;
                $product->save();
                

            }
          }

            session()->flash('success','Product add successfully');
            return response()->json([
                'status' =>true,
                'message'=> 'Product added successfully'
            ]);
        
           
        }else{
            return response()->json([
              'status'=>false,
              'errors' =>$validator->errors()
            ]);
        }


    }
    public function edit($id, Request $request)
    {
        $product =Product::find($id);
        $subCategories = SubCategory::where('category_id',$product->category_id)->get();
       
        $data = [];
        $data['subCategories'] =$subCategories;
        $data['product']=$product;
        $categories =Category::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        
       return view('admin.products.edit',$data);

    }
    public function destroy($id, Request $request){
        $products =Product::find($id);
        if(empty($products)){
            session()->flash('error','Product not found');
            return response()->json([
                'status'=>true,
                'message'=>"Products not found"
                ]); 
    
        }
        File::delete(public_path().'/uploads/product/'.$products->image);
        
        $products->delete();

        session()->flash('success',' Products deleted succesfully');
        return response()->json([
            'status'=>true,
            'message'=>"Products deleted successfully"
            ]); 

    }
}
