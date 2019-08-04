@foreach($collections as $collection)
<?php $thread = $collection->thread ?>
@if($thread)
<article class="thread{{$thread->id}}">
    <div class="row">
        <div class="col-xs-10 h5">
            <div class="row">
                <div class="col-xs-12">
                    <span>
                        <span class="badge newchapter-badge badge-tag">{{ $thread->channel()->channel_name }}</span>
                        <a href="{{ route('thread.show', $thread->id) }}" class="bigger-5">{{ $thread->title }}</a>
                        <small>
                            @if( !$thread->is_public )
                            <span class="glyphicon glyphicon-eye-close"></span>
                            @endif
                            @if( $thread->is_locked )
                            <span class="glyphicon glyphicon-lock"></span>
                            @endif
                            @if( $thread->no_reply )
                            <span class="glyphicon glyphicon-warning-sign"></span>
                            @endif
                        </small>
                        @if( $thread->is_bianyuan == 1)
                        <span class="badge bianyuan-tag badge-tag">限</span>
                        @endif
                        @if( $thread->recommended )
                        <span class="recommend-label">
                            <span class="glyphicon glyphicon-grain recommend-icon"></span>
                            <span class="recommend-text">推</span>
                        </span>
                        @endif
                        @if( $thread->tags->contains('tag_name', '精华') )
                        <span class="jinghua-label">
                            <span class="glyphicon glyphicon-thumbs-up jinghua-icon"></span>
                        </span>
                        @endif
                        @if($collection->updated)
                        <span class="badge newchapter-badge badge-tag">有更新</span>
                        @endif
                    </span>
                    &nbsp;by&nbsp;
                    <span >
                        @if($thread->author)
                        @if($thread->is_anonymous)
                        <span>{{ $thread->majia ?? '匿名咸鱼'}}</span>
                        @if((Auth::check()&&(Auth::user()->isAdmin())))
                        <span class="admin-anonymous"><a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a></span>
                        @endif
                        @else
                        <a href="{{ route('user.show', $thread->user_id) }}">{{ $thread->author->name }}</a>
                        @endif
                        @endif
                    </span>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-12">
                    <span class="smaller-5">{{ $thread->brief }}</span>
                </div>
            </div>
            <!-- thread title -->

            <!-- thread title end   -->
        </div>
        <div class="col-xs-2 h4">
            <span class = "pull-right">
                <span class="button-group">
                    <button type="button" class="dropdown-cog bigger-20" data-toggle="dropdown"><i class="fa fa-cog " aria-hidden="true"></i></button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a type="button" name="button" onclick="cancel_collection({{$collection->id}})">取消收藏</a></li>
                        <li>
                            <a type="button" name="button" class="{{ $collection->keep_updated?'':'hidden' }}" id="nomoreupdate{{$collection->id}}" onclick="collection_toggle_keep_update({{$collection->id}}, 'nomoreupdate')">取消收藏提醒</a>
                            <a type="button" name="button" class="{{ $collection->keep_updated? 'hidden':'' }}"  id="keepupdate{{$collection->id}}" onclick="collection_toggle_keep_update({{$collection->id}}, 'keep_update')">收藏提醒</a>
                        </li>
                        @foreach($groups as $group)
                        @if($show_collection_tab!=$group->id)
                        <li><a type="button" name="button" onclick="collection_change_group({{ $collection->id }},{{ $group->id }})">转移到《{{$group->name}}》</a></li>
                        @endif
                        @endforeach
                        @if($show_collection_tab!='default')
                        <li><a type="button" name="button" onclick="collection_change_group({{ $collection->id }},0)">转移到默认收藏</a></li>
                        @endif
                        @foreach($lists as $list)
                        @if(!$list->is_locked||Auth::user()->isAdmin())
                        <li><a href="{{route('review.create', ['thread' => $list->id, 'reviewee_id'=>$thread->id])}}">向《{{$list->title}}》清单添加本文书评</a></li>
                        @endif
                        @endforeach

                    </ul>
                </span>
            </span>
        </div>

        <div class="col-xs-12 h5 brief-0">
            @if($thread->last_component)
            <span class="grayout smaller-5"><a href="{{route('post.show', $thread->last_component_id)}}">《{{$thread->last_component->title}}》</a></span>
            <span class="grayout smaller-20">{{ $thread->add_component_at ? $thread->add_component_at->diffForHumans() :''}}</span>
            @else
            <span class="grayout smaller-20"><a href="{{ route('thread.showpost', $thread->last_post_id) }}">{{ $thread->last_post? StringProcess::simpletrim($thread->last_post->brief, 15):' ' }}</a></span>
            <span class="grayout smaller-20">{{ $thread->responded_at? $thread->responded_at->diffForHumans() :''}}</span>
            @endif

            <span class="pull-right smaller-20">
                @foreach($thread->tags as $tag)
                <i><a href="{{ route('books.index', StringProcess::mergeWithTag($tag->id, request()->all())) }}" class="{{$tag->tag_name==='完结'?'reminder-sign':''}}">{{ $tag->tag_name }}</a></i>
                @endforeach
            </span>


        </div>
    </div>
    <hr>
</article>
@endif
@endforeach
