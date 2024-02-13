<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LocationManagmentModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllLoations(): array
    {
        return $this->db
            ->select('l.*,cm.company_name')
            ->from('location_management as l')
            ->join('company_management as cm ', 'cm.cid = l.company_id')
            ->get()
            ->result();
    }

    public function getLocationWithWhere(int $id = 0): ?object
    {
        return $this->db
            ->select('l.*, c.company_name, u2.first_name as created_by, u3.first_name as modified_by')
            ->from('location_management as l')
            ->join('company_management as c', 'c.cid = l.company_id')
            ->join('user_management as u2', 'l.created_by = u2.user_id')
            ->join('user_management as u3', 'l.modified_by = u3.user_id', 'left')
            ->where(['l.pid' => $id])
            ->get()
            ->row();
    }
}
