@extends('forone::layouts.master')

@section('main')

    {!! Html::list_header([ ]) !!}


    {!! Html::datagrid($results) !!}

@stop