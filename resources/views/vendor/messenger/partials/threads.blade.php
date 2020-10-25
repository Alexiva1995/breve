<div class="card">
    <div class="card-header">
        <h4 class="card-title">Conversiones</h4>
    </div>

    <div class="card-body">
        @foreach ($threads as $key => $thread)
            @if ($thread->lastMessage)
                <a href="{{route('messenger', $thread->withUser->id)}}" class="thread-link">
                    <div class="row thread-row
                    @if (
                        !$thread->lastMessage->is_seen &&
                        $thread->lastMessage->sender_id != auth()->id())
                        unseen
                    @endif
                    @if ($thread->withUser->id === $withUser->id)
                        current
                    @endif
                    ">
                        <p class="thread-user">
                            {{$thread->withUser->name}}
                        </p>
                        <div class="thread-message">
                            @if ($thread->lastMessage->sender_id === auth()->id())
                                <i class="fa fa-reply" aria-hidden="true"></i>
                            @endif
                            {{substr($thread->lastMessage->message, 0, 20)}}
                        </div>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
</div>
