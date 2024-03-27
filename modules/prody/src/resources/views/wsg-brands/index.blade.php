@extends('panel::datatable')

@section('dashboard-content')
    
    @push('page-title')
        <div class="font-title fw-bold">
            Wsg Brands
        </div>
    @endpush

    <div class="container">
        <div class="row">
            @foreach ($wsgBrands as $wsgBrand)    
                <livewire:wsg-brand-card modelId="{{ $wsgBrand->uuid }}">
            @endforeach
    
        </div>
    </div>

@endsection
