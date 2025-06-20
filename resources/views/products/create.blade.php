@extends('layouts.app')
@section('header')
       <div class="d-flex justify-content-between align-items-center">
            <h2 class="h3 mb-0">Add New Product</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">Add New</li>
                </ol>
            </nav>
        </div>
@endsection

@section('content')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-box me-2"></i>Product Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('products.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            Product Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               value="{{ old('name') }}" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               placeholder="Enter product name"
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">
                                            SKU <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="sku" 
                                               id="sku" 
                                               value="{{ old('sku') }}" 
                                               class="form-control @error('sku') is-invalid @enderror" 
                                               placeholder="e.g., PROD-001"
                                               required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">
                                            Price <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="price" 
                                                   id="price" 
                                                   value="{{ old('price') }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   placeholder="0.00"
                                                   required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">
                                            Initial Quantity <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   name="quantity" 
                                                   id="quantity" 
                                                   value="{{ old('quantity') }}" 
                                                   min="0" 
                                                   class="form-control @error('quantity') is-invalid @enderror" 
                                                   placeholder="0"
                                                   required>
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Products
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-warning me-2">
                                        <i class="fas fa-undo me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Create Product
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            
            </div>
        </div>
    </div>

    <script>
      
            // Form validation
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const price = parseFloat(priceInput.value);
                const quantity = parseInt(quantityInput.value);

                if (price <= 0) {
                    e.preventDefault();
                    alert('Price must be greater than 0');
                    priceInput.focus();
                    return;
                }

                if (quantity < 0) {
                    e.preventDefault();
                    alert('Quantity cannot be negative');
                    quantityInput.focus();
                    return;
                }

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
                submitBtn.disabled = true;
            });

            // Reset button functionality
            const resetBtn = document.querySelector('button[type="reset"]');
            resetBtn.addEventListener('click', function() {
                setTimeout(updatePreview, 10); // Small delay to let form reset
            });
       
    </script>
@endsection