<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\Message;
use App\User;
use App\Profile;
use DB;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'message' => 'required'
        ]);

        // 'conversation_id' being passed in the request means the message...
        // ...is being definitely sent from within an EXISTING conversation (conversations.show)
        if($request->has("conversation_id")){
            // [security measure] Checks if the message sender is one of the two involved in the conversation
            $conversation = Conversation::find($request->input("conversation_id"));
            if(!($conversation->user_id == auth()->user()->id || $conversation->profile_id == auth()->user()->profile->id)){
                return Redirect("/conversations")->with("error", "UNAUTHORIZED ACCESS");
            }
            else 
                $conversation_id = $request->input("conversation_id");
        }
        else{
            // Creates a new conversation after checking if there's already an existing one (conversations.index/profiles.show)

            // I started/sent the first message of the conversation
            $myUserId = auth()->user()->id;
            $theirProfileId = $request->input("profile_id");
            // The other person started/sent the first message of the conversation
            $myProfileId = auth()->user()->profile->id;
            $theirUserId = Profile::find($request->input("profile_id"))->user_id;

            $conversation_data_array = DB::select("select * from conversations
                where user_id = $myUserId and profile_id = $theirProfileId
                or user_id = $theirUserId and profile_id = $myProfileId");
            
            if(empty($conversation_data_array)){
                $conversation = new Conversation;
                $conversation->user_id = auth()->user()->id;
                $conversation->profile_id = $request->input("profile_id");
                $conversation->save();
            }
            else
                $conversation = Conversation::find($conversation_data_array[0]->id);

            $conversation_id = $conversation->id;
        }

        // Creates a new message and assigns it to the conversation
        $message = new Message;
        $message->user_id = auth()->user()->id;
        $message->conversation_id = $conversation_id;
        $message->message = $request->input("message");
        $message->read = false;
        $message->save();

        return Redirect("/conversations/$conversation_id")->with('success', 'Message sent!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
