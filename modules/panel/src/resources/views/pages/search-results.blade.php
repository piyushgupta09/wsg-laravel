@extends('panel::datatable')

@section('dashboard-content')
    
    @push('page-title')
        Action Search
    @endpush

    <input type="search" name="search" class="form-control my-3">

    <p class="">Search Results for </p>

@endsection