@extends('master')

@section('page_title', 'Upgrade')

@section('content')

@include('upgrade._plans')

<x-paddle-button :url="$payLink" class="px-8 py-4">
    Subscribe Pro
</x-paddle-button>

@paddleJS

@endsection
