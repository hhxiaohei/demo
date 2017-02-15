@extends('forone.layouts.master')

@section('title', '操作日志')

@section('main')

    {!! Html::list_header([
        'search'=>true,
        'filters' => $results['filters'],
    ]) !!}

    {!! Html::datagrid($results) !!}


@stop