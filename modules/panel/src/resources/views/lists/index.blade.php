@extends('panel::datatable')

@section('dashboard-content')
    
    @push('page-title')
        @php
             switch ($modelName) {
                default: $name = $messages['list_page']; break;
            }
        @endphp
        {{ $name }}
    @endpush

    <livewire:datatables 
        :model="$model"
        :datatableClass="$datatable"
        :key="Str::plural($modelName, 2) . '-list-' . now()"
    />

@endsection
