@extends('forone::layouts.master')
@section('title', '查看'.$page_name)
@section('main')
    {!! Form::panel_start('查看'.$page_name) !!}

    {!! Form::group_text('user_id','会员ID',$data->id) !!}
    {!! Form::group_text('open_id','微信ID',$data->open_id) !!}

    </div>
    </div>

    {!! Html::list_header(['']) !!}
    {!! Html::datagrid($results) !!}

@stop