@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold">{{ $product->name }}</h2>
                <p class="text-gray-600">Price: ${{ number_format($product->price, 2) }}</p>
                <p class="text-gray-600">Stock: {{ $product->stock }}</p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('products.edit', $product) }}" class="px-3 py-2 bg-yellow-500 text-white rounded">Edit</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block"
                    onsubmit="return confirm('Delete this product?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded">Delete</button>
                </form>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('products.index') }}" class="text-indigo-600">Back to products</a>
        </div>
    </div>
@endsection
