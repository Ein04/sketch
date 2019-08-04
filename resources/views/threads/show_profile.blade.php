@extends('layouts.default')
@section('title', $thread->title )

@section('content')
<div class="container-fluid">
    <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
        @include('shared.errors')
        @include('threads._site_map')
        @include('threads._thread_profile')
        <div class="h4 text-center">
            <a href=" {{ route('thread.show', $thread->id) }} ">>>进入论坛模式查看/筛选更多评论内容</a>
        </div>
        @include('threads._posts')
        @if(Auth::check())
        <?php $post=null; ?>
        @include('threads._reply')
        @endif
    </div>
</div>
@stop
