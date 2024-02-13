<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ErrorPage extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        header('Content-type:application/json');
        echo json_encode(['status' => NOT_FOUND, 'message' => 'page not found', 'method' => $_SERVER['REQUEST_METHOD']]);
        exit;
    }
}
