<?php

use App\Tag;

if(!function_exists('timeAgo')){
    function timeAgo($date){
        $secs = ((new DateTime())->getTimestamp() - (new DateTime($date))->getTimestamp());
        $secIntervals = array(0, 60, 3600, 86400, 604800, 2592000, 31536000);
        $timeLabels = array("now", "seconds", "minutes", "hours", "days", "weeks", "months", "years");
        
        for($j = 0; $j < count($secIntervals); $j++){
            if($secs < 60 && $j < 2){
                if($secs < 0)
                    return "now";
                else
                    return "$secs seconds ago";
            }
            else{
                if($secs < $secIntervals[$j]){
                    $i = floor(abs($secs/$secIntervals[$j-1]));
                    $period = $timeLabels[$j];
                    if($secs > 31536000){
                        $i = floor(abs($secs/$secIntervals[$j]));
                        $period = $timeLabels[$j+1];
                    }
                
                    if($i>1)
                        return "$i $period ago";
                    else 
                        return "$i ".rtrim($period,'s')." ago";
                }
            }
        }
    }
}

function handleTags($article, $request, $updating){
    $tags = explode(",", $request);

    // These PHP array functions will lowercase and remove white space from the tags
    // They will also remove duplicate and empty tags
    $tags = array_filter(array_unique(array_map("strtolower", array_map("trim", $tags))), 'strlen');

    // This will seperate the new tags from the ones that already exist in the database
    $Tags = Tag::get()->pluck('name')->toArray();
    $newTags = array_diff($tags, $Tags);
    $existingTags = array_intersect($tags, $Tags);

    // If we're updating the article (articles.edit) instead of creating one (articles.create)
    // This will seperate the updated tags to three types:
    // 1: The brand new tags that have been added are already in $newTags
    // 2: The new tags that already exist in the database (other articles) are overwritten in $existingTags
    // 3: The tags that have been removed are put in $removedTags
    if($updating){
        $articleOriginalTags = $article->tags()->get()->pluck('name')->toArray();

        if(!empty($tags)){
            $existingTags = array_diff($existingTags, $articleOriginalTags);
            $removedTags = array_diff($articleOriginalTags, $tags);
    
            // This will find the tags that are removed and dettach them from the article
            if(!empty($removedTags)){
                foreach($removedTags as $tag){
                    $tag = Tag::where('name', $tag)->get();
                    $article->tags()->detach($tag);
                }
            }
        }
        else{
            // In case the user removed all tags in the edit input
            foreach($articleOriginalTags as $tag){
                $tag = Tag::where('name', $tag)->get();
                $article->tags()->detach($tag);
            }
        }
    }

    // This will add the new tags to the database and attach them to the article
    if(!empty($newTags)){
        foreach($newTags as $newTag){
            $tag = new Tag();
            $tag->name = $newTag;
            $tag->save();      
            $article->tags()->attach($tag);
        }
    }
    
    // This will find the existing tags in the database and attach them to the article
    if(!empty($existingTags)){
        foreach($existingTags as $existingTag){
            $tag = Tag::where('name', $existingTag)->get();
            $article->tags()->attach($tag);
        }
    }
}

?>