@extends('panel::layouts.app')

@section('body')
<div class="">
    @if (config('brand.welcome.video'))
        <video autoplay muted loop class="bg-content">
            <source src="{{ asset(config('brand.welcome.video')) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @else
        <img src="{{ asset(config('brand.welcome.image')) }}" alt="{{ config('brand.welcome.title') }}" class="bg-content" >
    @endif
    <div class="content">
        <div class="logo" style="margin: -2rem">
            <img src="{{ asset(config('brand.logo')) }}" alt="{{ config('brand.welcome.title') }}" width="200" style="filter: brightness(0) invert(1);">
        </div>
        <p class="fs-4 font-title fw-bold my-4">{{ config('brand.welcome.description') }}</p>
        <a href="{{ config('brand.welcome.link') }}" class="{{ config('brand.welcome.btn-class') }}">{{ config('brand.welcome.button') }}</a>
    </div>
</div>
@endsection

<style scoped>
    body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            min-height: 100vh;
            background: #000; /* Background color for the full page */
        }
        .bg-content {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: #fff; /* Text color for the content */
        }
        .logo {
            /* Your logo styles */
        }
        .get-started-button {
            margin-top: 20px;
            /* Your button styles */
        }
</style>