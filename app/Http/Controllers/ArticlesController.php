<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticlesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=>["index", "show"]]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::orderBy("created_at", "desc")->get();
        return view("articles.index", compact("articles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("articles.create");
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
            'title' => 'required',
            'content' => 'required',
            'feature' => 'nullable|image|max:5999'
        ]);

        if ($request->hasFile('feature')) {
            $filenameWithExtension = $request->file("feature")->getClientOriginalName();
            $extension = $request->file("feature")->getClientOriginalExtension();
            $filenameWithoutExtension = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $filenameToStore = $filenameWithoutExtension."_".time().".".$extension;

            $request->file("feature")->storeAs("public/features", $filenameToStore);
        }
        else
            $filenameToStore = "noimage.jpg";

        $article = new Article;
        $article->user_id = auth()->user()->id;
        $article->title = $request->input("title");
        $article->content = $request->input("content");
        $article->feature = $filenameToStore;
        $article->save();

        return redirect('/articles')->with("success", "Article published succesfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        $article = Article::find($article->id);
        return view("articles.show", compact("article"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        $article = Article::find($article->id);
        if(auth()->user()->id != $article->user_id)
            return redirect('/articles/'.$article->id)->with("error", "You cannot access this page!");
        return view("articles.edit", compact("article"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'feature' => 'nullable|image|max:5999'
        ]);

        if ($request->hasFile('feature')) {
            $filenameWithExtension = $request->file("feature")->getClientOriginalName();
            $extension = $request->file("feature")->getClientOriginalExtension();
            $filenameWithoutExtension = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $filenameToStore = $filenameWithoutExtension."_".time().".".$extension;

            $request->file("feature")->storeAs("public/features", $filenameToStore);
        }

        $article = Article::find($article->id);
        //$article->user_id = auth()->user()->id;
        $article->title = $request->input("title");
        $article->content = $request->input("content");
        if($request->hasFile("feature"))
        {
            Storage::delete("public/features/".$article->feature);
            $article->feature = $filenameToStore;
        }
        $article->save();

        return redirect('/articles/'.$article->id)->with("success", "Article updated succesfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article = Article::find($article->id);
        
        if(auth()->user()->id != $article->user_id)
            return redirect('/articles/'.$article->id)->with("error", "You cannot access this page!");

        if($article->feature != "noimage.jpg")
            Storage::delete("public/features/".$article->feature);
        $article->delete();
        return redirect('/articles')->with("success", "Article deleted succesfully!");
    }
}
