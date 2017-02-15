@extends('forone::layouts.master')

@section('main')

    {!! Html::list_header([
    'search'=>true,
    ]) !!}

    {!! Html::datagrid($results) !!}

@stop