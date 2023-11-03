<?php
use Jenssegers\Agent\Agent;

function getMobile() {

    $agent = new Agent();
    return $agent->isMobile();
}

function compareDateTime($date1, $date2) {
    if (strlen($date1) == 0 || strlen($date2) == 0) {
        return false;
    }

    $date_1 = new DateTime($date1);
    $date_2 = new DateTime($date2);

    $difference = $date_1->diff($date_2);
    $diffSec = $difference->s;
    $diffMin = $difference->i;
    $diffHour = $difference->h;
    $diffDay = $difference->d;
    $diffMonth = $difference->m;
    $diffYear = $difference->y;

    if ($diffYear > 0) {
        return $diffYear."year";
    } else if ($diffMonth > 0) {
        return $diffMonth."month";
    } else if ($diffDay > 0) {
        return $diffDay."day";
    } else if ($diffHour > 0) {
        return $diffHour."hour";
    } else if ($diffMin > 0) {
        return $diffMin."min";
    } else if ($diffSec > 0) {
        return $diffSec."sec";
    }
    return false;
}
?>
