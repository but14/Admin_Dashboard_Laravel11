<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
         $totalOrders =Order::where('status','!=','cancelled')->count();
         $totalProducts =Product::count();
         $totalCustomer =User::where('role',1)->count();
         $totalRevenue = Order::where('status','!=','canceled')->sum('grand_total');
        return view('admin.dashboard',[
            'totalOrders'=>$totalOrders,
            'totalProducts'=>$totalProducts,
            'totalCustomer'=>$totalCustomer,
            'totalRevenue'=>$totalRevenue
        ]);
        // $admin =Auth::guard('admin')->user();
        // echo 'Welcome'.$admin->name.'<a href"'.route('admin.logout').'">Logout<a/>';
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
