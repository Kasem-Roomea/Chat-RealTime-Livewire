<?php

namespace App\Http\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ChatLivewire extends Component
{
    public $sender;
    public $message;
    public $messages;

    public function MessageMount($userId)
    {
        $this->messages =Message::where('user_id' , Auth::user()->id)->where('receiver_id',$userId)->orWhere('receiver_id',Auth::user()->id)->where('user_id' ,$userId )->orderBy('id')->get();
    }
    public function getSender($userId)
    {
        $this->sender = User::findOrFail($userId);
        $this->messages =Message::where('user_id' , Auth::user()->id)->where('receiver_id',$userId)->orWhere('receiver_id',Auth::user()->id)->where('user_id' ,$userId )->orderBy('id')->get();
        $updateSeen = Message::where('user_id' , Auth::user()->id)->where('receiver_id',$userId)->get();

        foreach($updateSeen as $seen)
        {
                $seen->update([
                    'is_seen'=>1,
                ]);
        }
    }
    public function resetForm ()
    {
        $this->message = '';
    }


    public function SendMessage ()
    {
        $data = new Message();
        $data->message = $this->message;
        $data->user_id = $this->sender->id;
        $data->receiver_id = Auth::user()->id;
        $data->save();
        $this->resetForm();
        $this->messages =Message::where('user_id' , Auth::user()->id)->where('receiver_id',$this->sender->id)->orWhere('receiver_id',Auth::user()->id)->where('user_id' ,$this->sender->id )->orderBy('id')->get();
    }


    public function render()
    {
        $sender = $this->sender;
        $messages =$this->messages ;
        $users = User::all();
        return view('livewire.chat-livewire' , compact('users' , 'messages' ));
    }
}
