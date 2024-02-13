<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReportsModel extends MY_Model
{

    public function getLogTags()
    {
        return $this->db->select('lt.*,lm.location_name')
            ->from('log_table as lt')
            ->join('location_management as lm', 'lm.pid=lt.location_id', 'INNER')
            ->get()
            ->result();
    }
    public function getScannedUnscannedTags(int $auditId = 0, bool $isScanned = true)
    {
        return $this->db->select('a.*')->from('assets as a')
            ->join('location_management as l', 'a.location_id=l.pid', 'INNER')
            ->join('audit_management as am', 'ON am.location_id=l.pid', 'INNER')
            ->where(['a.status' => $isScanned, 'am.aid' => $auditId])
            ->get()
            ->result();
    }
}
