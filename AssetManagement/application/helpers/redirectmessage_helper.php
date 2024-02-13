<?php
function RedirectMessageLink($message, $color, $path)
{
    $CI = &get_instance();
    $CI->session->set_flashdata('message', $message);
    $CI->session->set_flashdata('color', $color);
    redirect($path);
}
