@extends('layouts.app')

@section('content')
<h2>Update Order Status</h2>

<form method="POST" action="{{ route('orders.update', $order->id) }}">
    @csrf
    @method('PUT')

    <label>Status</label>
    <select name="status" required>
        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>

    <button type="submit">Update Status</button>
</form>
@endsection
