@extends('layouts.app')

@section('title', 'Customer Details')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-semibold">{{ $customer->name }}</h2>
                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                <p class="text-sm text-gray-500">{{ $customer->phone ?? '—' }}</p>
            </div>
            <div class="text-right">
                <a href="{{ route('orders.create') }}?customer_id={{ $customer->id }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i> New Order
                </a>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-lg font-medium">Orders</h3>
            @if ($customer->orders->isEmpty())
                <p class="text-sm text-gray-500 mt-2">This customer has no orders yet.</p>
            @else
                <div class="mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($customer->orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}<div
                                            class="text-xs text-gray-400">{{ $order->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @foreach ($order->orderItems as $item)
                                            <div class="text-sm">{{ $item->product->name }} <span
                                                    class="text-gray-400">×{{ $item->quantity }}</span></div>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4">${{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <a href="{{ route('orders.show', $order->id) ?? '#' }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                                        <a href="{{ route('orders.edit', $order->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection
