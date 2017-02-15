@extends('forone.layouts.master')
@section('main')
    {!! Html::list_header([
    'new'    => true,
    'search' => true,
    ]) !!}
    {!! Html::datagrid($results) !!}
@stop