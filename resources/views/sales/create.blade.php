@extends('layouts.app')
@section('header')
    <h2 class="h3 mb-0">Create Sales Order</h2>
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    
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

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('sales.store') }}" method="POST" id="salesOrderForm">
                            @csrf

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-box me-2"></i>Select Products
                                </h6>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="productsTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="35%">Product</th>
                                                <th width="15%">Available</th>
                                                <th width="15%">Unit Price</th>
                                                <th width="15%">Quantity</th>
                                                <th width="15%">Total</th>
                                                <th width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="productRows">
                                            <!-- Product rows will be added here -->
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addProductRow()">
                                    <i class="fas fa-plus me-2"></i>Add Product
                                </button>
                            </div>

                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h5 class="card-title">Order Summary</h5>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Items:</span>
                                                <span id="itemCount">0</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Subtotal:</span>
                                                <span class="fw-bold" id="subtotal">$0.00</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Total Amount:</span>
                                                <span class="fw-bold text-success h5" id="total">$0.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('sales.list') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn" >
                                    <i class="fas fa-save me-2"></i>Create Order
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let productIndex = 0;
        const products = @json($products);

        function addProductRow() {
            const tbody = document.getElementById('productRows');
            const row = document.createElement('tr');
            row.id = `product-row-${productIndex}`;
            
            row.innerHTML = `
                <td>
                    <select name="products[${productIndex}][product_id]" class="form-select product-select" onchange="updateProductInfo(${productIndex})" required>
                        <option value="">Select Product</option>
                        ${products.map(product => `<option value="${product.id}" data-price="${product.price}" data-quantity="${product.quantity}">${product.name} (${product.sku})</option>`).join('')}
                    </select>
                </td>
                <td>
                    <span class="badge bg-info" id="available-${productIndex}">-</span>
                </td>
                <td>
                    <span id="price-${productIndex}">$0.00</span>
                </td>
                <td>
                    <input type="number" name="products[${productIndex}][quantity]" class="form-control quantity-input" min="1" onchange="updateRowTotal(${productIndex})" required>
                </td>
                <td>
                    <span class="fw-bold" id="row-total-${productIndex}">$0.00</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProductRow(${productIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            
            tbody.appendChild(row);
            productIndex++;
            updateSubmitButton();
        }

        function removeProductRow(index) {
            document.getElementById(`product-row-${index}`).remove();
            updateGrandTotal();
            updateSubmitButton();
        }

        function updateProductInfo(index) {
            const select = document.querySelector(`select[name="products[${index}][product_id]"]`);
            const option = select.selectedOptions[0];
            
            if (option.value) {
                const price = parseFloat(option.dataset.price);
                const available = parseInt(option.dataset.quantity);
                
                document.getElementById(`price-${index}`).textContent = `$${price.toFixed(2)}`;
                document.getElementById(`available-${index}`).textContent = available;
                
                const quantityInput = document.querySelector(`input[name="products[${index}][quantity]"]`);
                quantityInput.max = available;
                quantityInput.value = '';
                
                updateRowTotal(index);
            } else {
                document.getElementById(`price-${index}`).textContent = '$0.00';
                document.getElementById(`available-${index}`).textContent = '-';
                document.getElementById(`row-total-${index}`).textContent = '$0.00';
            }
            updateSubmitButton();
        }

        function updateRowTotal(index) {
            const select = document.querySelector(`select[name="products[${index}][product_id]"]`);
            const quantityInput = document.querySelector(`input[name="products[${index}][quantity]"]`);
            
            if (select.value && quantityInput.value) {
                const price = parseFloat(select.selectedOptions[0].dataset.price);
                const quantity = parseInt(quantityInput.value);
                const available = parseInt(select.selectedOptions[0].dataset.quantity);
                
                if (quantity > available) {
                    alert(`Only ${available} units available for this product`);
                    quantityInput.value = available;
                    return;
                }
                
                const total = price * quantity;
                document.getElementById(`row-total-${index}`).textContent = `$${total.toFixed(2)}`;
            } else {
                document.getElementById(`row-total-${index}`).textContent = '$0.00';
            }
            
            updateGrandTotal();
        }

        function updateGrandTotal() {
            let total = 0;
            let itemCount = 0;
            
            document.querySelectorAll('[id^="row-total-"]').forEach(element => {
                const amount = parseFloat(element.textContent.replace('$', ''));
                if (!isNaN(amount) && amount > 0) {
                    total += amount;
                    itemCount++;
                }
            });
            
            document.getElementById('itemCount').textContent = itemCount;
            document.getElementById('subtotal').textContent = `$${total.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        function updateSubmitButton() {
            const rows = document.querySelectorAll('#productRows tr');
            const submitBtn = document.getElementById('submitBtn');
            
            let hasValidRows = false;
            rows.forEach(row => {
                const select = row.querySelector('select');
                const input = row.querySelector('input');
                if (select && input && select.value && input.value) {
                    hasValidRows = true;
                }
            });
            
            // submitBtn.disabled = !hasVali    dRows;
        }

        // Add first row on page load
        document.addEventListener('DOMContentLoaded', function() {
            addProductRow();
        });

        // Form submission validation
        document.getElementById('salesOrderForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('#productRows tr');
            if (rows.length === 0) {
                e.preventDefault();
                alert('Please add at least one product to the order');
                return;
            }
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Order...';
            // submitBtn.disabled = true;
        });
    </script>
@endsection