<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserManagmentModel extends MY_Model
{

    public $table = 'user_management';

    public function __construct()
    {
        parent::__construct();
    }
    public function getAllActiveLoationswithCompanyName(): array
    {
        return $this->db
            ->select('l.*,cm.company_name')
            ->from('location_management as l')
            ->join('company_management as cm ', 'cm.cid = l.company_id')
            ->where(['l.status' => 1])
            ->get()
            ->result();
    }
    public function getAllUsersWithLocation(): array
    {
        return $this->db
            ->select('um.*,l.location_name')
            ->from('user_management as um')
            ->join('location_management as l', 'l.pid = um.location_id')
            ->get()
            ->result();
    }
    public function getAllUsersWithLocationWithId(int $id = 0): array
    {
        return $this->db
            ->select('um.*,l.location_name')
            ->from('user_management as um')
            ->join('location as l', 'l.pid = um.location_id')
            ->where(['user_id' => $id])
            ->get()
            ->row();
    }

    public function getUserProfileWithWhere(int $id = 0): ?object
    {
        return $this->db
            ->select('u1.*, u2.first_name as created_by, u3.first_name as modified_by,l.location_name')
            ->from('user_management as u1')
            ->join('location_management as l', 'l.pid = u1.location_id')
            ->join('user_management as u2', 'u1.created_by = u2.user_id')
            ->join('user_management as u3', 'u1.modified_by = u3.user_id', 'left')
            ->where(['u1.user_id ' => $id])
            ->get()
            ->row();
    }
    public function getUserActivitiesWithWehere(int $id = 0): array
    {
        $check = $this->db
            ->where(['user_id' => $id])
            ->order_by('last_login', 'DESC')
            ->get('loginactivity')
            ->result();
        if (!empty($check)) {
            return $check;
        } else {
            return [];
        }
    }
}
