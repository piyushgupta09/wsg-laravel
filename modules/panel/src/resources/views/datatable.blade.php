@extends('panel::layouts.app')

@section('main')

    <x-panel::dashboard.adminlte>
      
      @yield('dashboard-content')

    </x-panel::dashboard.adminlte>

    <livewire:app-toast  />
    <livewire:alert-box  />

@endsection
