{!! Form::form_text('title','标题','标题') !!}
@if(isset($show)  && $show)
    {!! Form::file_viewer('img','图片') !!}
    @else
    {!! Form::single_file_upload('img','图片') !!}
@endif