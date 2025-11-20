@extends('themes.' . config('app.settings.theme') . '.layout')

@section('content')
    @livewire('frontend.order')
@endsection