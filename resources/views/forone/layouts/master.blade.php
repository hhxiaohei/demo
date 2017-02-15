@extends('forone::layouts.base')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/forone/components/datetimepicker-master/jquery.datetimepicker.css') }}">
    <link href="{{asset('vendor/forone/components/videojs/video-js.css')}}" rel="stylesheet">
@endsection

@section('app')

@include('forone.partials.aside')

<div id="content" class="app-content" role="main">
    <div class="box">
        @include('forone::partials.navbar')
        <div class="box-row">
            <div class="box-cell">
                <div class="box-inner padding">
                    @yield('main')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@if(isset($result))
    <style>
        code {
            float: left;
            text-align: left;
        }
    </style>
    <div class="remodal" data-remodal-id="result">
        <button data-remodal-action="close" class="remodal-close"></button>
        <textarea class="form-control" rows="5">
            {!! $result !!}
        </textarea>
        </p>
        <br>
        <button data-remodal-action="confirm" class="remodal-confirm">OK</button>
    </div>

@endif

@section('js')
    <script src="{{ asset('vendor/forone/components/datetimepicker-master/jquery.datetimepicker.js') }}"></script>
    <script src="{{ asset('vendor/forone/components/videojs/video.js') }}"></script>
    @if(isset($result))
        <script>
            var inst = $('[data-remodal-id=result]').remodal();
            inst.open();
        </script>
    @endif
@endsection