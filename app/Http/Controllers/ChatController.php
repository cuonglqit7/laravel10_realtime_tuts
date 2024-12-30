<?php

namespace App\Http\Controllers;

use App\Events\GreetingSent;
use App\Events\MessageSent;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    //
    public function show()
    {
        return view('chat.show');
    }

    public function messageRecieved(Request $request)
    {

        $rules = [
            'message' => 'required',
        ];

        $request->validate($rules);

        broadcast(new MessageSent($request->user(), $request->message));

        return response()->json('Message broadcast');
    }

    public function greetingRecieved(Request $request, User $reciever)
    {
        if ($reciever->id != $request->user()->id) {
            broadcast(new GreetingSent($reciever, "{$request->user()->name} đã chào bạn"));
            broadcast(new GreetingSent($request->user(), "Bạn đã chào {$reciever->name}"));
            return "Lời chào từ {$request->user()} đến {$reciever->name}";
        }
        return "Bạn không thể tự nhận tin cho mình được";
    }
}
