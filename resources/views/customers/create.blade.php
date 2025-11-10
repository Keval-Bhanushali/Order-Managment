@extends('layouts.app')

@section('content')
<h2>Add Customer</h2>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<form method="POST" action="{{ route('customers.store') }}">
    @csrf
    <label>Name</label>
    <input type="text" name="name" value="{{ old('name') }}" required>
    @error('name')<span>{{ $message }}</span>@enderror

    <label>Email</label>
    <input type="email" name="email" value="{{ old('email') }}" required>
    @error('email')<span>{{ $message }}</span>@enderror

    <label>Phone</label>
    <input type="text" name="phone" value="{{ old('phone') }}">
    @error('phone')<span>{{ $message }}</span>@enderror

    <button type="submit">Add Customer</button>
</form>
@endsection
