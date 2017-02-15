@extends('forone.layouts.master')

@section('title', '查看'.$page_name)

@section('main')

    {!! Form::panel_start('查看'.$page_name) !!}
    {!! Html::json($data) !!}
@stop