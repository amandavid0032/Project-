<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AssetManagementModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCurrentCompnayProjectName()
    {
        return 0;
    }

    public function getAllAsset(): array
    {
        return $this->db
            ->select('a.*,lm.location_name')
            ->from('assets as a')
            ->join('location_management as lm ', 'lm.pid = a.location_id')
            ->get()
            ->result();
    }

    public function getAssetProfileWithWhere(int $id = 0): ?object
    {
        return $this->db
            ->select('a.*,lm.location_name , u2.first_name as created_by')
            ->from('assets as a')
            ->join('location_management as lm','lm.pid=a.location_id')
            ->join('user_management as u2', 'a.created_by = u2.user_id')
            ->where(['a.tid ' => $id])
            ->get()
            ->row();
    }
}
