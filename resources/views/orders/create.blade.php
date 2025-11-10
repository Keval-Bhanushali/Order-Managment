@extends('layouts.app')

@section('content')
<h2>Create Order</h2>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('orders.store') }}">
    @csrf
    <label>Select Customer</label>
    <select name="customer_id" required>
        @foreach($customers as $customer)
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
        @endforeach
    </select>

    <h3>Products</h3>
    <div id="products-wrapper">
        <div>
            <select name="products[0][product_id]" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                @endforeach
            </select>

            <input type="number" name="products[0][quantity]" min="1" required>
        </div>
    </div>

    <button type="button" onclick="addProduct()">Add Another Product</button>

    <button type="submit">Place Order</button>
</form>

<script>
let productIndex = 1;
function addProduct() {
    const wrapper = document.getElementById('products-wrapper');
    const div = document.createElement('div');
    div.innerHTML = `
        <select name="products[${productIndex}][product_id]" required>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
            @endforeach
        </select>
        <input type="number" name="products[${productIndex}][quantity]" min="1" required>
    `;
    wrapper.appendChild(div);
    productIndex++;
}
</script>

@endsection