@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Create New Order</h2>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}" id="orderForm" class="space-y-6">
            @csrf
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Customer</label>
                <select name="customer_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select a customer</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                            {{ $customer->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Order Items</h3>
                    <div class="text-right">
                        <span class="text-lg font-bold text-gray-900">Total: $</span>
                        <span id="orderTotal" class="text-lg font-bold text-gray-900">0.00</span>
                    </div>
                </div>

                <div id="products-wrapper" class="space-y-4">
                    <div class="product-row flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex-grow">
                            <select name="products[0][product_id]" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-select">
                                <option value="">Select a product</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}">
                                        {{ $product->name }} - ${{ number_format($product->price, 2) }} (Stock:
                                        {{ $product->stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <input type="number" name="products[0][quantity]" min="1" required
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-quantity"
                                placeholder="Qty">
                        </div>
                        <div class="w-32 text-right">
                            <span class="product-subtotal font-medium">$0.00</span>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-800 remove-product"
                            style="display: none;">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="button" onclick="addProduct()"
                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Another Product
                </button>
            </div>

            <div class="pt-5 border-t border-gray-200">
                <button type="submit"
                    class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Place Order
                </button>
            </div>
        </form>
    </div>

    <script>
        let productIndex = 1;

        function calculateSubtotal(row) {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.product-quantity');
            const subtotalSpan = row.querySelector('.product-subtotal');

            if (select.value && quantity.value) {
                const option = select.options[select.selectedIndex];
                const price = parseFloat(option.dataset.price);
                const subtotal = price * parseInt(quantity.value);
                subtotalSpan.textContent = '$' + subtotal.toFixed(2);
            } else {
                subtotalSpan.textContent = '$0.00';
            }

            calculateTotal();
        }

        function calculateTotal() {
            const subtotals = document.querySelectorAll('.product-subtotal');
            let total = 0;

            subtotals.forEach(span => {
                total += parseFloat(span.textContent.replace('$', ''));
            });

            document.getElementById('orderTotal').textContent = total.toFixed(2);
        }

        function validateStock(row) {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.product-quantity');

            if (select.value && quantity.value) {
                const option = select.options[select.selectedIndex];
                const stock = parseInt(option.dataset.stock);
                const qty = parseInt(quantity.value);

                if (qty > stock) {
                    quantity.setCustomValidity(`Only ${stock} items available in stock`);
                } else {
                    quantity.setCustomValidity('');
                }
            }
        }

        function addProduct() {
            const wrapper = document.getElementById('products-wrapper');
            const div = document.createElement('div');
            div.className = 'product-row flex items-center space-x-4 p-4 bg-gray-50 rounded-lg';
            div.innerHTML = `
        <div class="flex-grow">
            <select name="products[${productIndex}][product_id]" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-select">
                <option value="">Select a product</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                        {{ $product->name }} - ${{ number_format($product->price, 2) }} (Stock: {{ $product->stock }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="w-32">
            <input type="number" name="products[${productIndex}][quantity]" min="1" required 
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 product-quantity"
                   placeholder="Qty">
        </div>
        <div class="w-32 text-right">
            <span class="product-subtotal font-medium">$0.00</span>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800 remove-product">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;
            wrapper.appendChild(div);

            // Show all remove buttons when there's more than one product
            document.querySelectorAll('.remove-product').forEach(btn => btn.style.display = 'block');

            // Add event listeners for the new row
            const newRow = wrapper.lastElementChild;
            setupRowEventListeners(newRow);

            productIndex++;
        }

        function setupRowEventListeners(row) {
            const select = row.querySelector('.product-select');
            const quantity = row.querySelector('.product-quantity');
            const removeBtn = row.querySelector('.remove-product');

            select.addEventListener('change', () => {
                validateStock(row);
                calculateSubtotal(row);
            });

            quantity.addEventListener('input', () => {
                validateStock(row);
                calculateSubtotal(row);
            });

            removeBtn.addEventListener('click', () => {
                row.remove();
                const remainingRows = document.querySelectorAll('.product-row');
                if (remainingRows.length === 1) {
                    remainingRows[0].querySelector('.remove-product').style.display = 'none';
                }
                calculateTotal();
            });
        }

        // Setup event listeners for the initial row
        document.addEventListener('DOMContentLoaded', () => {
            const initialRow = document.querySelector('.product-row');
            setupRowEventListeners(initialRow);
        });
    </script>

@endsection
