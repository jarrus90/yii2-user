<?php

namespace jarrus90\User\Admin;

class Bootstrap implements BootstrapInterface {
    
}

/* Days to publicate end */
$days = [1, 3, 5, 29];
$curDayStart = mktime(0, 0, 0, date('n'), date('j') + 1);
foreach ($days as $day) {
    $daysTimestamp[86400 * $day] = 86400 * ($day + 2);
}
$query = 'SELECT * FROM items INNER JOIN users ON items.user_id = users.id where status != 1 AND status_sent = 0 AND (';
foreach ($daysTimestamp as $theRestOfTime => $restOfTime2) {
    $tempTime = $curTime + $restOfTime2;
    $restTime = $curTime + $theRestOfTime;
    $status = ($theRestOfTime / 86400);
    $part .= "((publicated_to > {$restTime}) AND (publicated_to < {$tempTime})) and status_sent > $status) OR ";
}

$part = substr($part, 0, -4);
$part .= ") ";
$query .= $part . ' LIMIT 100';
