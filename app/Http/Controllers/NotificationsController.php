<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Profile;
use App\Comment;
use App\User;
use DB;

class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function notify(Profile $profile)
    {
        $profile = Profile::find($profile->id);

        if (Gate::allows('notify', $profile)) {
            $profile->user->unreadNotifications
                ->whereIn('type', array(
                                    "App\Notifications\NewUserFollower",
                                    "App\Notifications\NewArticleUserComment",
                                    "App\Notifications\NewArticleProfileLike"))
                ->markAsRead();
            $originalNotifications = $profile->user->notifications->sortByDesc('created_at');
            $alteredNotifications = array();

            foreach($originalNotifications as $notification){
                switch ($notification->type) {
                    case "App\Notifications\NewUserFollower":
                    {
                        $newNotification = array();

                        $newNotification["url"] = $notification->data["url"];
                        $follower = User::find($notification->data["followerUserId"]);
                        $newNotification["userPic"] = $follower->profile->profileImage();
                        $newNotification["text"] = "<a href='".$newNotification["url"]."'>".$follower->profile->fullname."</a>"." followed you";
                        if ($notification->data["followedBack"])
                            $newNotification["text"] .= " back";
    
                        array_push($alteredNotifications, $newNotification);
                    }
                    break;
    
                    case "App\Notifications\NewArticleUserComment":
                    {
                        $newNotification = array();
                        
                        $newNotification["url"] = $notification->data["url"];
                        $commenter = User::find($notification->data["commenterUserId"]);
                        $newNotification["userPic"] = $commenter->profile->profileImage();
                        $comment = Comment::find($notification->data["commentId"]);
                        $newNotification["text"] = "<a href='/profiles/".$commenter->profile->id."'>".$commenter->profile->fullname."</a>"." commented on <a href='".$newNotification["url"]."'>your article</a><br>&nbsp;&nbsp;&nbsp;&nbsp;\"".$comment->comment."\"";
    
                        array_push($alteredNotifications, $newNotification);
                    }
                    break;
    
                    case "App\Notifications\NewArticleProfileLike":
                    {
                        $newNotification = array();
                        
                        $newNotification["url"] = $notification->data["url"];
                        $liker = User::find($notification->data["likerUserId"]);
                        $newNotification["userPic"] = $liker->profile->profileImage();
                        $newNotification["text"] = "<a href='/profiles/".$liker->profile->id."'>".$liker->profile->fullname."</a>"." liked <a href='".$newNotification["url"]."'>your article</a>";
    
                        array_push($alteredNotifications, $newNotification);
                    }
                    break;
    
                    default:
                    break;
                }
            }
    
            $alteredNotifications = collect($alteredNotifications)->map(function ($alteredNotification) {
                return (object) $alteredNotification;
            });
        }
        else
            abort(403);

        return view("profiles.notifications", compact("alteredNotifications"));
    }
}
