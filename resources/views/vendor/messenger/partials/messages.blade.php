@php
$authId = auth()->id();
@endphp
@if ($messages)

    @foreach ($messages as $key => $message)
        @if ($message->sender_id === $authId)
            <div class="row message-row justify-content-end mt-2">
                <i class="fa fa-ellipsis-h fa-2x pull-right" aria-hidden="true">
                    <div class="delete" data-id="{{$message->id}}">Delete</div>
                </i>
                <div title="{{date('d-m-Y h:i A' ,strtotime($message->created_at))}}" class="sent">
                    {!! $message->message !!}
                </div>
            </div>
        @else
        <div class="row message-row justify-content-start mt-2">
            <div title="{{date('d-m-Y h:i A' ,strtotime($message->created_at))}}" class="received">
                {!! $message->message !!}
            </div>
            <i class="fa fa-ellipsis-h fa-2x pull-left" aria-hidden="true">
                <div class="delete" data-id="{{$message->id}}">Delete</div>
            </i>
        </div>
        @endif
    @endforeach
@endif
