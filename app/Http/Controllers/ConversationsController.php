<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conversation;
use App\Message;
use App\User;
use App\Profile;
use DB;
use Storage;

class ConversationsController extends Controller
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
        // Deletes conversations that have been opened but remained empty when the inbox is opened (conversations.index)
        foreach (auth()->user()->conversations as $conversation) {
            if(count($conversation->messages) == 0){
                Storage::deleteDirectory("public/conversations/convo_".$conversation->id."_u".$conversation->user_id."_p".$conversation->profile_id);
                $conversation->delete();
            }
        }
        foreach (auth()->user()->profile->conversations as $conversation) {
            if(count($conversation->messages) == 0){
                Storage::deleteDirectory("public/conversations/convo_".$conversation->id."_u".$conversation->user_id."_p".$conversation->profile_id);
                $conversation->delete();
            }
        }

        // Displays conversations ordered by the most recently created
        $sent = [
            "user_id" => (auth()->user()->id)
        ];
        $recieved = [
            "profile_id" => (auth()->user()->profile->id)
        ];

        $conversations = Conversation::where($sent)
            ->orWhere($recieved)
            ->orderBy("created_at", "desc")
            ->get();
        $users = User::all()->except(['id', auth()->user()->id]);

        return view("conversations.index", compact("conversations", "users"));
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
            'profile_id' => 'required'
        ]);

        // Creates a new conversation after checking if there's already an existing one
        // $sent = [
        //     "user_id" => (auth()->user()->id),
        //     "profile_id" => ($request->input("profile_id"))
        // ];
        // $recieved = [
        //     "user_id" => (Profile::find($request->input("profile_id"))->user_id),
        //     "profile_id" => (auth()->user()->profile->id)
        // ];
        // $conversation = Conversation::where($sent)->orWhere($recieved)->first();

        // The above "where/orWhere" Eloquent collection-based search didn't work
        // Had to resort to a more explicit brute SQL-based search
        
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
            Storage::makeDirectory("public/conversations/convo_".$conversation->id."_u".$conversation->user_id."_p".$conversation->profile_id);
            $conversation->path = "/storage/conversations/convo_".$conversation->id."_u".$conversation->user_id."_p".$conversation->profile_id;
            $conversation->save();
        }
        else
            $conversation = Conversation::find($conversation_data_array[0]->id);
        
        return Redirect("/conversations/".$conversation->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $conversation = Conversation::find($id);
        // Protects conversations from uninvolved users accessing them via URL
        if(!($conversation->user_id == auth()->user()->id || $conversation->profile_id == auth()->user()->profile->id))
            return Redirect("/conversations")->with("error", "UNAUTHORIZED ACCESS");

        // Marks new incoming messages as read
        foreach ($conversation->messages as $message) {
            if ((auth()->user()->id != $message->user_id) && !($message->read)){
                $message_id = $message->id;
                DB::update("UPDATE `messages` SET `read` = '1' WHERE `messages`.`id` = $message_id;");
            }
        }
        $conversation->refresh();

        return view("conversations.show", compact("conversation"));
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