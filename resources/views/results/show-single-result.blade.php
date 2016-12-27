@extends('results.layouts.master')

@section('title')

@endsection

@section('styles')
    <style>
            .result-container{
                position: absolute;
                top:20px;
                z-index: 1;
                width:100%;
            }
    </style>
@append

@section('content')

    @include('results.includes.navbar')

    <div class="result-container">
        {!! $resultHTML !!}
    </div>

@endsection