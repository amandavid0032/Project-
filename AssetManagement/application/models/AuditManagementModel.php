<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuditManagementModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllAudit(): array
    {
        return $this->db
            ->select('am.*,lm.location_name')
            ->from('audit_management as am')
            ->join('location_management as lm ', 'lm.pid = am.location_id')
            ->get()
            ->result();
    }
    public function getAuditProfileWithWhere(int $id = 0): ?object
    {
        return $this->db
            ->select('a1.*,lm.location_name, u2.first_name as created_by, u3.first_name as modified_by')
            ->from('audit_management as a1')
            ->join('user_management as u2', 'a1.created_by = u2.user_id')
            ->join('user_management as u3', 'a1.modified_by = u3.user_id', 'left')
            ->join('location_management as lm ', 'lm.pid = a1.location_id')
            ->where(['a1.aid ' => $id])
            ->get()
            ->row();
    }
}
