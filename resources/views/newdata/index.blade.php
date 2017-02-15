@extends('forone.layouts.master')
@section('main')
    {!! Html::list_header([
        'search'  => '输入城市或名字进行检索' ,
        'filters' => $results['filters'],
        'buttons' => $results['buttons'],
        'time' => true,
    ]) !!}
    {!! Html::datagrid($results) !!}
@stop