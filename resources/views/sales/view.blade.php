@extends('layouts.app')
@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0">Sales Order Details</h2>
        <div>
            <a href="{{ route('sales.pdf', $salesOrder) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-file-pdf me-2"></i>Download PDF
            </a>
            <a href="{{ route('sales.list') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Orders
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-alt me-2"></i>{{ $salesOrder->order_number }}
                            </h5>
                            @if($salesOrder->status === 'confirmed')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check me-1"></i>Confirmed
                                </span>
                            @elseif($salesOrder->status === 'pending')
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Pending
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times me-1"></i>Cancelled
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-2">Order Information</h6>
                                <p class="mb-1"><strong>Order Number:</strong> {{ $salesOrder->order_number }}</p>
                                <p class="mb-1"><strong>Date:</strong> {{ $salesOrder->created_at->format('F d, Y') }}</p>
                                
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Created By:</strong> {{ $salesOrder->user->name }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    @if($salesOrder->status === 'confirmed')
                                        <span class="text-success">Confirmed</span>
                                    @elseif($salesOrder->status === 'pending')
                                        <span class="text-warning">Pending</span>
                                    @else
                                        <span class="text-danger">Cancelled</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <h6 class="text-muted mb-3">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>SKU</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($salesOrder->items as $item)
                                        <tr>
                                            <td>
                                                <div class="fw-medium">{{ $item->products->name }}</div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $item->products->sku }}</span>
                                            </td>
                                            <td class="text-end">${{ number_format($item->unit_price, 2) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                            </td>
                                            <td class="text-end fw-bold">${{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Total Quantity:</th>
                                        <th class="text-end text-success fs-5">{{ $salesOrder->items->sum('quantity') }}</th>
                                    </tr>
                                     <tr>
                                        <th colspan="4" class="text-end">Total Amount:</th>
                                        <th class="text-end text-success fs-5">${{ number_format($salesOrder->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection