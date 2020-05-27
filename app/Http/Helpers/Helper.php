<?php

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

?>