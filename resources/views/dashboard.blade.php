@extends('layouts.app')
@section('header')
       <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Dashboard</h2>
           
        </div>
@endsection

@section('content')
    <div class="container-fluid">
            <div class="row">
                <!-- Statistics Cards -->
                <div class="col-12">
                    <div class="row mb-4">
                        <!-- Total Sales Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Sales
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ${{ number_format($totalSales, 2) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-primary"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Orders Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Orders
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($totalOrders) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-cart fa-2x text-success"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Low Stock Alert Card -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Low Stock Items
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $lowStockProducts->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Low Stock Products Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alert
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($lowStockProducts->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead class="table-warning">
                                            <tr>
                                                <th>Product Name</th>
                                                <th>Current Stock</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($lowStockProducts as $product)
                                                <tr>
                                                    <td>{{ $product->name }}</td>
                                                    <td>
                                                        <span class="badge bg-warning text-dark">
                                                            {{ $product->quantity }}
                                                        </span>
                                                    </td>
                                                    <td>${{ number_format($product->price, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <h5 class="text-success">All products are well stocked!</h5>
                                    <p class="text-muted">No low stock products at this time.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-clock me-2"></i>Recent Orders
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($recentOrders->count() > 0)
                                @foreach($recentOrders as $order)
                                    <div class="d-flex align-items-center border-bottom py-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="fas fa-receipt text-white"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3 flex-grow-1">
                                            <div class="fw-bold">
                                                Order #{{ $order->id }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $order->user->name ?? 'Guest' }}
                                            </div>
                                            <div class="text-success fw-bold">
                                                ${{ number_format($order->total_amount, 2) }}
                                            </div>
                                        </div>
                                        <div class="text-muted small">
                                            {{ $order->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h6 class="text-muted">No recent orders</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection