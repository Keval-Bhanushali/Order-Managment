@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">Edit Product</h2>

        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('name')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input type="number" name="price" step="0.01" value="{{ old('price', $product->price) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('price')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('stock')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Update
                    Product</button>
                <a href="{{ route('products.index') }}" class="ml-3 text-sm text-gray-600">Cancel</a>
            </div>
        </form>
    </div>
@endsection
