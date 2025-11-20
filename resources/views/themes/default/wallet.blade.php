@extends('themes.' . config('app.settings.theme') . '.layout')

@section('header')
    <title>{{ __('Wallet') }} - {{ config('app.settings.name') }}</title>
@endsection

@section('content')
    @livewire('frontend.wallet')
@endsection