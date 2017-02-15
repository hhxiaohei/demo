<p>单文件上传同图片上传</p>
{!! Form::form_text('title','标题','标题') !!}
{!! Form::multi_file_upload('file','多文件上传') !!}