<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model
{
  public function login($data)
	{
		return $this->db->get_where('user',$data);
	}

	public function check_user_lupa_password($data)
	{
		return $this->db->where('username',$data->username)
		->where('email',$data->email)
		->where('status','0')
		->where('token',$data->token)
		->get('user_lupa_password')->row();
	}

	public function update_password($username,$password)
	{
		return $this->db->where('username',$username)->update('user',['password'=>$password]);
	}

	public function register($data)
	{
		return $this->db->insert('user',$data);
	}

	public function insert_user_lupa_password($data)
	{
		return $this->db->insert('user_lupa_password',$data);
	}

	public function check_user($email)
	{
		return $this->db->where("email",$email)->get('user')->row();
	}

	public function update_status_user_lupa($username)
	{
		return $this->db->where('username',$username)->update('user_lupa_password',['status'=>'1']);
	}
}

