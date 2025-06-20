@extends('layouts.app')
@section('header')
    <div class="d-flex justify-content-between align-items-center">
        <h2 class="h3 mb-0">Sales Orders List</h2>
        <div>
            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New Order
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid py-4">
      
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-3 mb-0" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-3 mb-0" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order Number</th>
                                <th>Date </th>
                                <th>User</th>
                                <th>Items</th>
                                <th>Total Amount </th>
                                <th>Status</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($salesOrders as $order)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-primary">{{ $order->order_number }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $order->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td>
                                        {{ $order->user->name }}
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $order->items->count() }} items</span>
                                        <div><small class="text-muted">{{ $order->items->sum('quantity') }} qty</small></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">${{ number_format($order->total_amount, 2) }}</div>
                                    </td>
                                    <td>
                                        @if($order->status === 'confirmed')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Confirmed
                                            </span>
                                        @elseif($order->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('sales.show', $order) }}" 
                                               class="btn btn-sm btn-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($order->status === 'pending')
                                                <form action="{{ route('sales.confirm', $order) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            title="Confirm Order" 
                                                            onclick="return confirm('Are you sure you want to confirm this order?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('sales.pdf', $order) }}" 
                                               class="btn btn-sm btn-secondary" title="Download PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <h5>No Sales Orders Found</h5>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($salesOrders->hasPages())
                    <div class="card-footer bg-white border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $salesOrders->firstItem() }} to {{ $salesOrders->lastItem() }} 
                                of {{ $salesOrders->total() }} results
                            </div>
                            <div>
                                {{ $salesOrders->appends(request()->query())->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

       
    </div>

    <script>
        
        function confirmOrder(orderId) {
            if (confirm('Are you sure you want to confirm this order?')) {
                document.getElementById(`confirm-form-${orderId}`).submit();
            }
        }

    </script>
@endsection