<?php
class ModelUser extends CI_Model
    {
    public function simpanToken($data = null)
        {
        $this->db->insert('user_token', $data);
        }

    public function simpanData($data = null)
        {
        $this->db->insert('data_pegawai', $data);
        }

    public function cekData($where = null)
        {
        return $this->db->get_where('data_pegawai', $where);
        }
    }
