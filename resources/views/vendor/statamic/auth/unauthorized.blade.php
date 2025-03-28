@php use function Statamic\trans as __; @endphp

@extends('statamic::outside')
@section('title', __('Unauthorized'))

@section('content')
<div class="logo pt-20">
    <img src="/assets/contenidos/logo.png" alt="{{ config('statamic.cp.custom_cms_name') }}" class="white-label-logo">
</div>

<div class="card auth-card mx-auto text-center text-gray-700">
    <div class="mb-6">{{ __('No autorizado') }}</div>
    
    <a class="btn-primary" href="{{ cp_route('logout') }}?redirect={{ cp_route('login') }}">{{ __('Regresar') }}</a>
</div>

@endsection
