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

        // Starts here: this part checks if any new messages have been sent in deleted (excluded) conversations
        // If they have, the deleted (excluded) conversation will re-appear with only the new messagess displayed
        // If not, the conversation will stay deleted (hidden)
        $excludedConversationsArray = auth()->user()->excluding()->select('conversation_id', 'num_msgs_when_excluded')->get();
        $excludedConversations = array();
        foreach($excludedConversationsArray as $exc){
            $num_msgs_when_excluded = $exc['num_msgs_when_excluded'];
            $num_msgs_now  = Conversation::find($exc['conversation_id'])->messages->count();
            if($num_msgs_now == $num_msgs_when_excluded)
                array_push($excludedConversations, $exc['conversation_id']);
        }
        // Ends here.

        $conversations = Conversation::where($sent)
            ->orWhere($recieved)
            ->orderBy("created_at", "desc")
            ->get()
            ->except($excludedConversations);
        
        // I should be able to select only the users that I'm following and the ones following me
        // The ones that I blocked or blocked me will not be shown of course
        $followers = Auth()->user()->profile->followers()->pluck('user_id');
        $followings = Auth()->user()->following->pluck('user_id');
        
        $users = User::whereIn('id', $followers)->orWhereIn('id', $followings)->get();
        // Ends here.

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
        
        return redirect()->action('ConversationsController@show', compact('conversation'));
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
        $messages = $conversation->messages;

        // Checks if this conversation has been deleted (excluded) before, at least once
        $exists = DB::table('conversation_user')
                    ->select()
                    ->where([
                        'user_id' => auth()->user()->id,
                        'conversation_id' => $conversation->id])
                    ->first();
        
        // If it has, Therefore checks if there are any new messages that have been sent to it
            // Then it will ONLY return/display the new messages in the (conversations.show) page
            // Also if you access the conversation through (profiles.show) it will show 0 messages
        if(!is_null($exists)){
            $num_msgs_when_excluded = $exists->num_msgs_when_excluded;
            $num_msgs_now  = Conversation::find($exists->conversation_id)->messages->count();
            //dd($num_msgs_now." ".$num_msgs_when_excluded);
            if($num_msgs_now > $num_msgs_when_excluded){
                $n = $num_msgs_now - $num_msgs_when_excluded;
                $messages = $conversation->messages
                                ->sortBy("created_at", 0, true)
                                ->take($n);
                $messages = $messages->sortBy("created_at", 0, false);
            }
            else{
                $messages = null;
            }
        }

        // Checks if one of the people in the conversation has the other person blocked
        $blocked = false;
        $user = User::find($conversation->user_id);
        $profile = Profile::find($conversation->profile_id);
        $user_blocked_profile = ($user->blocking->contains($profile));
        $user_blocked_by_profile = ($profile->user->blocking->contains($user->profile));
        //dd("user_blocked_profile: $user_blocked_profile, user_blocked_by_profile: $user_blocked_by_profile");
        if($user_blocked_profile || $user_blocked_by_profile)
            $blocked = true;
        // Ends here.

        return view("conversations.show", compact("conversation", "messages", "blocked"));
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