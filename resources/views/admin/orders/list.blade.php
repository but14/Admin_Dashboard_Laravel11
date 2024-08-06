@extends('admin.layouts.app')
@section('content')
<section class="content">
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('categories.create')}}" class="btn btn-primary">New Category</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Default box -->
    <div class="container-fluid">
        @include('admin.message')
        <div class="card">
            <form action="" method="GET">
            <div class="card-header">
                <div class="card-title">
                    <button type="button" onclick="window.location.href='{{route("orders.index")}}'" class="btn btn-default btn-sm">Reset</button>
                </div>
                
                <div class="card-tools">
                    <div class="input-group input-group" style="width: 250px;">
                        <input  value="{{Request::get('keyword')}}" type="text" name="keyword" class="form-control float-right" placeholder="Search">
    
                        <div class="input-group-append">
                          <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                          </button>
                        </div>
                      </div>
                </div>
           
            </div>
        </form>     
            <div class="card-body table-responsive p-0">								
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th width="60">Order#</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Phone</th>
                           
                            <th width="100">Status</th>
                            <th width="100">Amount</th>
                            <th>Date Purchased</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->isNotEmpty())
                          @foreach($orders as $order)
                        
                        <tr>
                            <td><a href="{{route('orders.detail',[$order->id])}}">{{$order->id}}</a></td>
                            <td>{{$order->name}}</td>
                            <td>{{$order->email}}</td>
                            <td>{{$order->phonenumber}}</td>
                            <td>
                                @if($order->status =='pending')
                                <span class="badge bg-danger">Pending</span>
                                @elseif($order->status =='shipped')
                                <span class="badge bg-info">Delivered</span>
                                
                                @else
                                <span class="badge bg-succces">Delivered</span>
                                @endif
                            </td>
                            <td>
                         {{ number_format($order->grand_total,2)}}
                            </td>
                            <td>
                                {{  \Carbon\Carbon::parse($order->create_at)->format('d M,Y')}}
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr><td colspan="5">Records Not Found</td></tr>
                        @endif
                        
                        
                       
                       
                     
                    </tbody>
                </table>										
            </div>
            <div class="card-footer clearfix">
                {{$orders->links() }}
                {{-- <ul class="pagination pagination m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">«</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul> --}}
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
    

@endsection
 @section('customJs')

 @endsection