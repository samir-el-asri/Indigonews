<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\User;
use DB;

class ExcludesController extends Controller
{
    public function store(Conversation $conversation){
        
        // Checks if the conversation has already been deleted (excluded) before at least once,
        // if it has, it will just update the current number of messages and updated_at properties

        $user_id = auth()->user()->id;
        $conversation_id = $conversation->id;
        $exists = DB::table('conversation_user')->select('id')->where(['user_id' => $user_id, 'conversation_id' => $conversation_id])->first();
        
        if(!is_null($exists)){
            DB::table('conversation_user')
                ->where('id', $exists->id)
                ->update([
                    'num_msgs_when_excluded' => $conversation->messages->count(),
                    'updated_at' => date('Y-m-d H:i:s', strtotime(now()))
                ]);
        }
        else{
            auth()->user()->excluding()->attach($conversation,['num_msgs_when_excluded' => $conversation->messages->count()]);
        }

        return redirect()->action('ConversationsController@index')->with("success", "Conversation deleted.");
    }
}
