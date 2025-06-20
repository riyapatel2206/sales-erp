@extends('layouts.app')
@section('header')
   <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Product Details</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">

            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h4 class="card-title mb-1">{{ $product->name }}</h4>
                                <p class="text-muted mb-0">SKU: {{ $product->sku }}</p>
                            </div>
                            <div class="text-end">
                                @if($product->isLowStock())
                                    <span class="badge bg-danger fs-6 mb-2">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Low Stock
                                    </span>
                                @else
                                    <span class="badge bg-success fs-6 mb-2">
                                        <i class="fas fa-check-circle me-1"></i>In Stock
                                    </span>
                                @endif
                                <div class="h4 text-success mb-0">${{ number_format($product->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                       
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i>Product Information
                        </h6>
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td class="fw-bold" width="200">Product Name:</td>
                                        <td>{{ $product->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">SKU:</td>
                                        <td>
                                            <code class="bg-light p-1 rounded">{{ $product->sku }}</code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Unit Price:</td>
                                        <td class="text-success fw-bold">${{ number_format($product->price, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Current Stock:</td>
                                        <td>
                                            <span class="badge {{ $product->isLowStock() ? 'bg-danger' : 'bg-success' }} fs-6">
                                                {{ $product->quantity }} units
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Stock Status:</td>
                                        <td>
                                            @if($product->quantity == 0)
                                                <span class="text-danger"><i class="fas fa-times-circle me-1"></i>Out of Stock</span>
                                            @elseif($product->isLowStock())
                                                <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Low Stock</span>
                                            @else
                                                <span class="text-success"><i class="fas fa-check-circle me-1"></i>In Stock</span>
                                            @endif
                                        </td>
                                    </tr>
                                   
                                    <tr>
                                        <td class="fw-bold">Created Date:</td>
                                        <td>{{ $product->created_at->format('F d, Y \a\t h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Last Updated:</td>
                                        <td>{{ $product->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if($product->isLowStock())
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Low Stock Alert!</strong> This product has {{ $product->quantity }} units remaining. Consider restocking soon.
                            </div>
                        @endif

                        @if($product->quantity == 0)
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>Out of Stock!</strong> This product is currently unavailable for new sales orders.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

           
        </div>
    </div>


@endsection