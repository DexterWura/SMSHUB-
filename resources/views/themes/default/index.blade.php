@extends('themes.' . config('app.settings.theme') . '.layout')

@section('header')
    @if(isset($page))
    {!! $page->header !!}
    <title>{{ $page->title }} - {{ config('app.settings.name') }}</title>
    @else
    <title>{{ config('app.settings.name') }}</title>
    @endif
    @if(!isset($page) && !isset($number))
    {!! config('app.settings.homepage.header') !!}
    @endif
@endsection

@section('content')
    @if(isset($page))
    @livewire('frontend.page', ['page' => $page])
    @elseif(isset($number))
    @livewire('frontend.number', ['number' => $number])
    @else
    @livewire('frontend.home')
    @endif
@endsection

@section('footer')
    @if(!isset($page) && !isset($number))
    {!! config('app.settings.homepage.footer') !!}
    @endif
@endsection