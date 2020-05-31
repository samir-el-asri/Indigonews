<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Articles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except'=>["show"]]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        $profile = Profile::find($profile->id);
        return view("profiles.show", compact("profile"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {
        $profile = Profile::find($profile->id);
        if(auth()->user()->id != $profile->user_id)
            return redirect('/articles')->with("error", "You cannot access this page!");
        return view("profiles.edit", compact("profile"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        $data = request()->validate([
            'fullname' => 'required',
            'gender' => 'required',
            'birthday' => 'required',
            'bio' => 'required',
            'profile_image' => 'nullable|image|max:5999'
        ]);

        if ($request->hasFile('profile_image')) {
            if($profile->profile_image != "noimage.jpg")
                Storage::delete("public/profile_images/".$profile->profile_image);

            $filenameWithExtension = $request->file("profile_image")->getClientOriginalName();
            $extension = $request->file("profile_image")->getClientOriginalExtension();
            $filenameWithoutExtension = pathinfo($filenameWithExtension, PATHINFO_FILENAME);
            $filenameToStore = $filenameWithoutExtension."_".time().".".$extension;

            $request->file("profile_image")->storeAs("public/profile_images", $filenameToStore);

            $profile->update(array_merge(
                $data,
                ['profile_image' => $filenameToStore]
            ));
        }
        else{
            $profile->update($data);
        }

        return redirect('/profiles/'.$profile->id)->with([
            "profile" => $profile,
            "success" => "Profile updated succesfully!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profile  $profile
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $profile = Profile::find($profile->id);

        // Deletes profile image from storage
        if($profile->profile_image != "noimage.jpg")
            Storage::delete("public/profile_images/".$profile->profile_image);
        // Deletes profile
        $profile->delete();
        // Deletes all user's articles
        $articles = auth()->user()->articles;
        foreach ($articles as $article) {
            if($article->feature != "noimage.jpg")
                Storage::delete("public/features/".$article->feature);
            $article->delete();
        }
        // Deletes User (profile owner)
        auth()->user()->delete();

        return redirect('/articles')->with([
            "success" => "All account's articles deleted!",
            "success" => "Account permanently deleted!"
        ]);
    }
}
