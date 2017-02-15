@if(isset($show) && !empty($show))
    {!! Form::form_read('title','标题') !!}
    {!! Form::form_read('time','记录时间') !!}
@else
    {!! Form::form_text('title','标题','标题') !!}
    {!! Form::form_date('time','记录时间','2017-01-01') !!}
@endif
{!! Form::form_time('notetime','计划时间','2017-01-01 08:00:00') !!}
{!! Form::form_select('type', '类型', [
    ['label'=>'散文', 'value'=>'0'],
    ['label'=>'诗歌', 'value'=>'1']
],0.5,false) !!}
{!! Form::form_radio('level', '文章等级', [
    [0, 'A', true],
    [1, 'B'],
    [2, 'C'],
    [3, 'D'],
    [4, 'E'],
    [5, 'F']
], 0.5) !!}
{!! Form::form_tags_input('column','标签') !!}
{!! Form::ueditor('contents', '文章内容') !!}
{!! Form::form_area('note','摘要','摘要文字') !!}