<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Comment;
use App\Article;
use App\Notifications\NewArticleUserComment;
use DB;

class CommentsController extends Controller
{
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
            'comment' => 'required',
            'article_id' => 'required'
        ]);

        $comment = new Comment;
        $comment->comment = $request->input('comment');
        $comment->user_id = auth()->user()->id;
        $comment->article_id = $request->input('article_id');
        $comment->save();
        
        if($comment->article->user->id != auth()->user()->id)
            $comment->article->user->notify(new NewArticleUserComment(auth()->user()->id, "/articles/".$comment->article->id, $comment->id));

        return redirect('/articles/'.$comment->article_id)->with("success", "Comment posted succesfully!");
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
        $comment = Comment::find($id);
        $article_id = $comment->article_id;
        
        // This will delete the notification affiliated with this comment as well whether it was read or unread.
        $commentNotifications = DB::table('notifications')->select('id', 'data')->where(['type' => "App\Notifications\NewArticleUserComment"])->get();
        foreach($commentNotifications as $notification){
            $commentId = intval(explode(':', (explode(',', $notification->data))[2])[1]);
            if($commentId == $comment->id){
                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->delete();
            }
        }

        $comment->delete();

        return Redirect("/articles/".$article_id)->with("success", "Comment deleted successfully!");
    }
}
