<?php
$CI = &get_instance();

function checkUserSession()
{
    global $CI;
    return $CI->session->userdata();
}

function postAllowed()
{
    global $CI;
    if (count($CI->input->post()) <= 0 && $_SERVER['REQUEST_METHOD'] != 'POST') {
        return false;
    }
    return true;
}

function fileAllowed()
{
    if (count($_FILES) <= 0 && $_SERVER['REQUEST_METHOD'] != 'POST') {
        return false;
    }
    return true;
}

function postDataFilterhtml($data)
{
    global $CI;
    $data = trim($data);
    $data = htmlentities($data);
    $data = mysqli_real_escape_string($CI->db->conn_id, $data);
    return $data;
}

function getCurrentTime()
{
    $now = new DateTime();
    $now->setTimezone(new DateTimezone('Asia/Calcutta'));
    return $now->format('Y-m-d H:i:s');
}

function getFormatedDate(string $date = ''): string
{
    return date('Y-m-d H:i:s', strtotime($date));
}

function yearMonthDayDate(string $date = ''): string
{
    return date('Y-m-d', strtotime($date));
}

function getCurrentAgent()
{
    global $CI;
    $CI->load->library('user_agent');

    if ($CI->agent->is_browser()) {
        $agent = $CI->agent->browser() . ' ' . $CI->agent->version();
    } elseif ($CI->agent->is_robot()) {
        $agent = $CI->agent->robot();
    } elseif ($CI->agent->is_mobile()) {
        $agent = $CI->agent->mobile();
    } else {
        $agent = 'Unidentified User Agent';
    }
    return [$agent, $CI->agent->platform()];
}
function selectedValue($dataBaseValue, $matchWith): string
{
	return ($dataBaseValue == $matchWith) ? 'SELECTED' : '';
}
