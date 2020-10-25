@extends('layouts.client')

@section('css-styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{asset('vendor/messenger/css/messenger.css')}}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 threads">
            @include('messenger::partials.threads')
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        {{-- Perfil:  --}}
                        Chat Con - {{$withUser->name}} {{-- {{$withUser->email}} --}}
                        {{-- Chat Con - {{$withUser->name}} --}}
                    </h4>
                </div>

                <div class="card-body">
                    <div class="messenger">
                         @if( is_array($messages) )
                            @if (count($messages) === 20)
                                <div id="messages-preloader"></div>
                            @endif

                            <div id="messages-preloader"></div>
                        @else
                            <p class="start-conv">Conversation started</p>
                        @endif
                        <div class="messenger-body">
                            @include('messenger::partials.messages')
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <input type="hidden" name="receiverId" value="{{$withUser->id}}">
                    <textarea id="message-body" name="message" rows="2" placeholder="Type your message..."></textarea>
                    <input type="hidden" name="" id="toke_id" value="{{ csrf_token() }}">
                    <button type="submit" id="send-btn" class="btn btn-primary">SEND</button>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><h4></h4></div>

                <div class="panel-body">
                    <p>
                        <span>Name</span> 
                    </p>
                    <p>
                        <span>Email</span> {{$withUser->email}}
                    </p>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection

@section('js-scripts')
@routes

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
        var withId        = {{$withUser->id}},
            authId        = {{auth()->id()}},
            messagesCount = {{is_array($messages) ? count($messages) : '0'}};
            pusher        = new Pusher('{{config('messenger.pusher.app_key')}}', {
              cluster: '{{config('messenger.pusher.options.cluster')}}'
            });
    </script>
    <script src="{{asset('vendor/messenger/js/messenger-chat.js')}}" charset="utf-8"></script>
    <script>
        $(document).ready(function(){
            $('#message-body').summernote({
                placeholder: 'Un Nuevo Mensaje',
                tabsize: 2,
                height: 120,
                toolbar: [
                // ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                // ['para', ['ul', 'ol', 'paragraph']],
                // ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                // ['view', ['fullscreen', 'codeview', 'help']]
                ]
            })
        })
    </script>
@endsection
