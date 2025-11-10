@extends('layouts.app')

@section('content')
<h2>Customer List</h2>

<a href="{{ route('customers.create') }}">Add Customer</a>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr><td colspan="3">No customers found.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection