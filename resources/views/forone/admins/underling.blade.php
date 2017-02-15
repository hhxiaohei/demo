@extends('forone.layouts.master')
@section('head')
    <script>
        var itemChangeHandler = function (name, value) {
            updateFields();
        };
    </script>
@stop
@section('main')

    {!! Form::panel_start('编辑管理员下属') !!}
    {!! Form::model($admin,['method'=>'POST','route'=>['admin.admins.post_underling', 'id'=>$admin_id],'class'=>'form-horizontal']) !!}

    {!! Form::form_multi_select('admins', '管理员', $types, '选择管理员') !!}

    <div class="config"></div>

    {!! Form::panel_end('更新') !!}
    {!! Form::close() !!}

    <table class="table m-b-none tablet footable-loaded footable" data-sort="false" ui-jp="footable">
        <thead>
        <tr>
            <th data-toggle="true" class="footable-visible footable-first-column">名称</th>
            <th class="footable-visible">手机</th>
            <th data-hide="phone" class="footable-visible">邮箱</th>
            <th data-hide="phone" class="footable-visible footable-last-column">创建时间</th>
        </tr>
        </thead>
        <tbody>
        @foreach($under_lines as $under_line)
            <tr>
                <td class="footable-visible footable-first-column">{{ $under_line->name }}</td>
                <td class="footable-visible">{{ $under_line->mobile }}</td>
                <td class="footable-visible">{{ $under_line->email }}</td>
                <td class="footable-visible">{{ $under_line->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="10" class="text-center footable-visible"></td>
        </tr>
        </tfoot>
    </table>

@stop