<?php

namespace App\Http\Controllers\admin;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use  App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;   

use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();
        if(!empty($request->get('keyword'))){
        $categories = $categories->where('name','like','%'.$request->get('keyword').'%');
            
        }
        $categories = $categories->paginate(10);
 return view('admin.category.list',compact('categories'));
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $request )
    {
        $validator =Validator::make($request->all(),[
            'name'=>'required',
            'slug'=>'required|unique:categories',
        ]);
        if($validator->passes()){
            
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            
            $category->save();
        // Nhận tệp hình ảnh từ yêu cầu
        if (!empty($request->image_id)) {
            $tempImage =TempImage::find($request->image_id);
            $extArray =explode('.',$tempImage->name);
            $ext =last(($extArray));

            $newImageName = $category->id.'.'.$ext;
            $sPath = public_path().'/temp/'.$tempImage->name;
            $dPath = public_path().'/uploads/category/'.$newImageName;
            File::copy($sPath,$dPath);
            $category->image = $newImageName;
            $category->save();
            
    
        }


        
        


        session()->flash('success','Category added successfully');
        return response()->json([
            'status'=>true,
            'message'=>"Category added successfully"
            ]); 
        }else{
            return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
            ]);
        }

    }
    public function edit($categoryId,Request $request)
    {
        $category =Category::find($categoryId);
        if(empty($category))
        {
            return redirect()->route('categories.index');

        }
        
        return view('admin.category.edit',compact('category'));
    }
    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if (empty($category)) {
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $category->id . ',id',
        ]);
    
        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $oldImage =$category->image;
            // Kiểm tra nếu có hình ảnh mới được tải lên
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                if (!empty($tempImage)) {
                    // Lấy phần mở rộng của tệp hình ảnh
                    $extArray = explode('.', $tempImage->name);
                    $ext = end($extArray);
                    
                    // Tạo tên mới cho hình ảnh
                    $newImageName = $category->id.'-'.time().'.'.$ext;
                    
                    // Di chuyển hình ảnh từ thư mục tạm sang thư mục ảnh chính thức
                    $sPath = public_path().'/temp/'.$tempImage->name;
                    $dPath = public_path().'/uploads/category/'.$newImageName;
                    File::move($sPath, $dPath);
                    
                    // Cập nhật tên hình ảnh mới cho danh mục
                    $category->image = $newImageName;
                    
                    // Xóa hình ảnh cũ nếu tồn tại
                   
                        File::delete(public_path().'/uploads/category/'.$oldImage);
                    
                }
            }
    
            $category->save();
    
            session()->flash('success', 'Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => "Category updated successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function destroy($categoryId, Request $request){
        $category =Category::find($categoryId);
        if(empty($category)){
            session()->flash('error','Category not found');
            return response()->json([
                'status'=>true,
                'message'=>"Category not found"
                ]); 
    
        }
        File::delete(public_path().'/uploads/category/'.$category->image);
        
        $category->delete();

        session()->flash('success','Category deleted succesfully');
        return response()->json([
            'status'=>true,
            'message'=>"Category deleted successfully"
            ]); 

        
    }
}
