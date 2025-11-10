@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold">Products</h2>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i> New Product
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-300 text-green-800 rounded">{{ session('success') }}</div>
        @endif

        @if ($products->count())
            <div class="overflow-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($products as $product)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('products.show', $product) }}"
                                        class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                    <a href="{{ route('products.edit', $product) }}"
                                        class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                    <form action="{{ route('products.destroy', $product) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <p>No products yet. <a href="{{ route('products.create') }}" class="text-indigo-600">Create one.</a></p>
        @endif
    </div>

@endsection
