
{!! Form::group_text('name','用户名字','请输入用户名称') !!}
{!! Form::group_text('email','邮箱','请输入邮箱') !!}
{!! Form::group_text('mobile','手机号','请输入手机号') !!}
{!! Form::group_text('openid','微信ID','不要输入') !!}

@if (Entrust::hasRole('admin'))
    {!! Form::group_password('password','重置密码','请输入密码') !!}
@endif

@section('js')
    @parent
@stop
