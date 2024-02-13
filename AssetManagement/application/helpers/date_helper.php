<?php
function getCurrentTime()
{
    $now = new DateTime();
    $now->setTimezone(new DateTimezone('Asia/Calcutta'));
    $get_time = $now->format('Y-m-d H:i:s');
    return $get_time;
}
