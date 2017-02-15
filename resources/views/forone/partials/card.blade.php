@if(isset($data))
    @foreach($data as $item)
<div class="col-sm-3">
    {{--@if(isset($data))--}}
        {{--@foreach($data as $item)--}}
        <div class="md-list md-whiteframe-z0 m-b teal-500">
            <div class="md-list-item">
                <div class="md-list-item-left circle green">
                    <i class="mdi-action-grade i-24"></i>
                </div>
                <div class="md-list-item-content">
                    <h3 class="text-md">{{$item['label']}}</h3>
                    <a href="{{$item['href']}}"><small class="font-thin">{{$item['value']}}</small></a>
                </div>
            </div>
        </div>
        {{--@endforeach--}}
    {{--@endif--}}
</div>
    @endforeach
@endif
{{--<div class="col-sm-6">--}}
    {{--<div class="card @if(isset($blue)) blue @endif">--}}
        {{--<div class="card-heading">--}}
            {{--<h2>{{$title}}</h2>--}}
            {{--<small>{{$desc}}</small>--}}
        {{--</div>--}}
        {{--@if(isset($data))--}}
            {{--<div class="list-group list-group-lg no-bg">--}}

                {{--@foreach($data as $item)--}}
                    {{--<a href="{{$item['href']}}" class="list-group-item">--}}
                    {{--<span class="pull-right">--}}
                      {{--{{$item['value']}}--}}
                    {{--</span>--}}
                        {{--{{$item['label']}}--}}
                    {{--</a>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--@endif--}}
    {{--</div>--}}
{{--</div>--}}