<div class="container">
    <div class="row">
        <nav class="menu">
            <ul class="items">
                <li class="item" title="{{ Auth::user()->name }}">
                    <i class="fa fa-user" aria-hidden="true" ></i>
                </li>
                <li class="item item-active" title="Messages">
                    <i class="fa fa-commenting" aria-hidden="true"></i>
                </li>
                <li class="item" onclick="event.preventDefault();document.getElementById('logout-form').submit();" title="logout">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                    <a  href="{{ route('logout') }}">
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>

        <section class="discussions">
            <div class="discussion search">
                <div class="searchbar">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input type="text" placeholder="Search..."></input>
                </div>
            </div>
            <div wire:poll="render">
            @foreach($users as $user)
             @if($user->id != Auth::user()->id)

             @php

             $not_seen = App\Models\Message::where('user_id' , auth()->id())->where('receiver_id',$user->id)->where('is_seen' , 0)->get()??null;

             @endphp
                    <div class="discussion " wire:click = 'getSender({{$user->id}})'>
                        <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">
                            <div @if($user->is_online ==1)class="online" @endif></div>
                        </div>
                        <div class="desc-contact">
                            <p class="name">{{$user->name}}</p>
                            <p class="message">{{$user->lastMessageMe->first()->message??''}}</p>
                                   
                        </div>
                  
                        <div class="timer">

                            @if($user->last_active == null)
                            <span class="text-success">Connect</span>
                            @else
                                   {{$user->last_active}}
                            @endif
                               @if(filled($not_seen))
                                    <div class='bg-success text-white text-center fs-5' style="border-radius: 50%">{{$not_seen->count()}}</div>
                                     @endif
                        </div>

                    
                    </div>
                @endif
                @endforeach
            </div>

        </section>
        <section class="chat"  >
            @if(isset($sender))
            <div class="header-chat">
                <i class="icon fa fa-user-o" aria-hidden="true"></i>
                <p class="name">{{$sender->name}}</p>
                <i class="icon clickable fa fa-ellipsis-h right" aria-hidden="true"></i>
            </div>

            <div class="messages-chat" id="messagesChat" wire:poll="MessageMount({{$sender->id}})">
                @foreach($messages as $message)
                    @if($message->user_id == Auth::user()->id)
                        <div class="message">
                            <div class="photo" style="background-image: url(https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1050&q=80);">

                            </div>
                            <p class="text"> {{$message->message}} </p>
                        </div>
                        @if($message->created_at->format('Y-m-d') === now()->format('Y-m-d'))
                            <p class="time"> {{$message->created_at->format('H:i')}}</p>
                        @else
                            <p class="time"> {{$message->created_at->format('Y-m-d H:i')}}</p>
                        @endif
                     @elseif($message->user_id != Auth::user()->id)
                        <div class="message text-only">
                            <div class="response">
                                <p class="text"> {{$message->message}}</p>
                            </div>
                        </div>
                        @if($message->created_at->format('Y-m-d') === now()->format('Y-m-d'))
                        <p class="response-time time"> {{$message->created_at->format('H:i')}}</p>
                            @else
                            <p class="response-time time"> {{$message->created_at->format('Y-m-d H:i')}}</p>
                            @endif
                    @endif
                @endforeach

            </div>
            <div class="footer-chat" style="width:80%;">
                <form wire:submit.prevent = 'SendMessage' style="width:100%" >
                    <input type="text" class="write-message" placeholder="Type your message here" wire:model="message" ></input>
                    <button type="submit" id="send" class="btn btn-primary d-inline-block" style="height:100%"><i class=" fa fa-paper-plane " aria-hidden="true" style="height: 100%;"></i> Send</button>
                </form>
            </div>
            @endif
        </section>
    </div>
</div>
<script>

    var scroll  = document.getElementById('messagesChat');
    var send  = document.getElementById('send');
    send.onclick =  function  () {
        scroll.scrollTop = scroll.scrollHeight;
    }
</script>
