@extends('layouts.app')

@section('title', 'Edit Order')

@section('content')
    <div class="max-w-3xl mx-auto bg-white shadow rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Update Order Status</h2>

        <div class="mb-4">
            <div class="text-sm text-gray-700">Order #{{ $order->id }}</div>
            <div class="text-sm text-gray-500">Customer: {{ $order->customer->name }}</div>
            <div class="text-sm text-gray-500">Created: {{ $order->created_at->format('M d, Y H:i') }}</div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-medium">Items</h3>
            <ul class="mt-2 divide-y divide-gray-100">
                @foreach ($order->orderItems as $item)
                    <li class="py-2 flex justify-between items-center">
                        <div>
                            <div class="text-sm font-medium">{{ $item->product->name }}</div>
                            <div class="text-sm text-gray-500">Qty: {{ $item->quantity }}</div>
                        </div>
                        <div class="text-sm font-medium">${{ number_format($item->price, 2) }}</div>
                    </li>
                @endforeach
            </ul>
        </div>

        <form method="POST" action="{{ route('orders.update', $order->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('orders.index') }}" class="text-sm text-gray-600 hover:underline">Back to orders</a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Update
                    Status</button>
            </div>
        </form>

        @if ($order->status !== 'cancelled')
            <p class="mt-4 text-sm text-gray-500">Note: Cancelling an order will restore product stock.</p>
        @endif
    </div>

@endsection
