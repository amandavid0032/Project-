<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LoginModel extends MY_Model
{

    private $table = 'user_management';

    public function __construct()
    {
        parent::__construct();
    }

    public function checkLogin(array $whereData = [])
    {
        $query = $this->db->where($whereData)->get($this->table);
        if ($query->num_rows() != 0) {
            $userData = $query->row();
            if ($userData->status == 1) {
                $sessionArray = [
                    'id' => $userData->user_id,
                    'phone_number' => $userData->phone_number,
                    'user_type' => $userData->user_type
                ];
                $this->session->set_userdata($sessionArray);
                [$agent, $paltform] = getCurrentAgent();
                $insertData = [
                    'user_id' => $userData->user_id,
                    'last_login' => getCurrentTime(),
                    'ip_address' => $this->input->ip_address(),
                    'login_agent' => $agent,
                    'platform' => $paltform
                ];
                $this->insertData('loginactivity', $insertData);
                $this->updateData('user_management', ['user_id' => $userData->user_id], ['last_login' => getCurrentTime()]);

                return ['status' => SUCCESS, 'message' => "Welcome $userData->first_name $userData->last_name to WAVELINX TAG MANAGEMENT SYSTEM"];
            } else {
                return ['status' => NOT_ACTIVE_ANYMORE, 'message' => 'User not active any more '];
            }
        } else {
            return ['status' => FAILED, 'message' => 'Invalid Username/Password'];
        }
    }
}
