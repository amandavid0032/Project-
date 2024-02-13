<?php
class Vehicle_admin extends CI_Model 
{
	public function login_model()
	{
		$code_email_id=strtolower(stripcslashes(htmlspecialchars(trim($this->input->post('email_id')))));
		$user_password=stripcslashes(htmlspecialchars(trim($this->input->post('password'))));
		$salt=sha1('abcdefghijklmn');
		$password=$user_password.$salt;
		$user_password=sha1($password);
		$where_data=array(
				'code_email_id'=>$code_email_id,
				'pass'=>$user_password
			);
		$result=$this->db->where($where_data)->get('user_management');
		if($result->num_rows()==1)
		{
			$result = $result->row();
			if($result->status==1)
			{
				$management_user_data = array(
								'user_id'  => $result->user_id,
								'user_name'  => $result->first_name.' '.$result->last_name,
								'email'=>$result->email_id,
								'code_email_id'=>$result->code_email_id,
								'type'=>$result->user_type,
								);
				$this->session->set_userdata($management_user_data);
				$this->session->set_flashdata('succ_msg','Welcome '.ucfirst($this->session->userdata('user_name')).' To Site Manager');
				$this->session->set_flashdata('color_msg',"success");
				redirect('Vehicle_controller/dashboard');
			}
			else
			{
				$this->session->set_flashdata('succ_msg','Sorry! Your Account has been Deactivated');
				$this->session->set_flashdata('color_msg',"warning");
				redirect('Vehicle_controller/index');
			}	
		}
		else
		{
			$this->session->set_flashdata('succ_msg','Invalid Email id/Password');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/index');
		}
	}
	public function dashboard()
	{
		return array(
			'total_active_device_connected'=>$this->db->query('SELECT COUNT(device_name) as count FROM device_table WHERE status=1 and connect=1')->result()[0]->count,
			'total_active_device_free'=>$this->db->query('SELECT COUNT(device_name) as count FROM device_table WHERE status=0 and connect=0')->result()[0]->count,
			'total_tags'=>$this->db->query('SELECT COUNT(vehicle_id) as count FROM  vehicle')->result()[0]->count,
			'get_device_status'=>$this->get_device_status(),
			'get_live_count'=>$this->get_live_count(),
			'live_transaction_in'=>$this->live_transaction_in(),
			'live_transaction_out'=>$this->live_transaction_out(),
			);
	}

	public function my_profile()
	{
		return $this->db->where(['user_id'=>$this->session->userdata('user_id')])->get('user_management')->row();
	}
	public function get_all_management_user()
	{
		return $this->db->select('user_id,first_name,last_name,phone_no,code_email_id,email_id,user_type,status')->order_by('first_name','ASC')->get('user_management')->result();
	}
	public function total_rows()
	{
		return $this->db->query("SELECT vehicle_id FROM vehicle")->num_rows();
	}
	public function get_management_user($id)
	{
		return $this->db->select('user_id,first_name,last_name,phone_no,code_email_id,email_id,user_type,status,created_dt')->order_by('first_name','ASC')->where(['user_id'=>$id])->get('user_management')->row();
	}
	public function get_all_area()
	{
		return $this->db->order_by('area_name','ASC')->get('area')->result();
	}
	public function get_all_area_active()
	{
		return $this->db->where(['status'=>1])->order_by('area_name','ASC')->get('area')->result();
	}
	public function get_single_area($id)
	{
		return $this->db->where(["area_id"=>$id])->get('area')->row();
	}
	public function get_all_vehicle($limit,$offset)
	{
		return $this->db->order_by('created_dt','DESC')->limit($limit,$offset)->get('vehicle')->result();
	}
	public function get_single_vehcile($id)
	{
		return $this->db->query("SELECT v.*, CONCAT(cb.first_name,' ',cb.last_name) as created_by_full_name , CONCAT(mb.first_name,' ',mb.last_name) as modified_by_full_name  FROM vehicle as v LEFT JOIN user_management as cb ON v.created_by=cb.user_id LEFT JOIN user_management as mb  ON v.modified_by=mb.user_id WHERE v.vehicle_id=$id")->row();
	}
	public function get_single_vehcile_details($id)
	{
		return $this->db->where(['vehicle_id'=>$id])->get('vehicle')->row();
	}
	public function get_all_devices()
	{
		return $this->db->select('device_id,device_name,device_id_number,status,created_dt')->order_by('created_dt','DESC')->get('device_table')->result();
	}
	public function get_single_device($id)
	{
		return $this->db->query("SELECT d.*, CONCAT(cb.first_name,' ',cb.last_name) as created_by_full_name , CONCAT(mb.first_name,' ',mb.last_name) as modified_by_full_name FROM device_table as d LEFT JOIN user_management as cb ON d.created_by=cb.user_id LEFT JOIN user_management as mb  ON d.modified_by=mb.user_id WHERE d.device_id=$id")->row();
	}
	public function get_single_device_details($id)
	{
		return $this->db->where(['device_id'=>$id])->get('device_table')->row();
	}
	public function get_single_location_details($id)
	{
		return $this->db->where(['location_id'=>$id])->get('locations')->row();
	}
	public function report_area_wise()
	{
		$id=$this->input->post('area_id');
		$location_type=$this->input->post('location_type');
		$date_to=$this->input->post('date_to');
		$date_from=$this->input->post('date_from');

		if($location_type=='a')
		{
			return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,l.location_type,a.area_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id INNER JOIN area as a ON a.area_id=l.area_id  WHERE a.area_id=$id and c.status=1 and date('$date_to')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$date_from') ORDER BY dc.device_date_time DESC")->result();
		}
		else
		{
			return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,l.location_type,a.area_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id INNER JOIN area as a ON a.area_id=l.area_id  WHERE a.area_id=$id and l.location_type='$location_type' and c.status=1 and date('$date_to')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$date_from') ORDER BY dc.device_date_time DESC")->result();
		}	
	}	

	public function report_device_wise()
	{
		$id=$this->input->post('device_id');
		$date_to=$this->input->post('date_to');
		$date_from=$this->input->post('date_from');

		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,l.location_type,a.area_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id INNER JOIN area as a ON a.area_id=l.area_id  WHERE d.device_id_number='$id' and c.status=1 and date('$date_to')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$date_from') ORDER BY dc.device_date_time DESC")->result();
			
	}
	public function report_location_wise()
	{
		$location_id=$this->input->post('location_id');
		$date_to=$this->input->post('date_to');
		$date_from=$this->input->post('date_from');

		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,l.location_type,a.area_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id INNER JOIN area as a ON a.area_id=l.area_id  WHERE l.location_id=$location_id and c.status=1 and date('$date_to')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$date_from') ORDER BY dc.device_date_time DESC")->result();
	}
	public function report_vehicle_wise()
	{
		$vehicle_tag=strtolower(htmlentities(stripcslashes(trim($this->input->post('vehicle_tag')))));
		$date_to=$this->input->post('date_to');
		$date_from=$this->input->post('date_from');
		
		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,l.location_type,a.area_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id INNER JOIN area as a ON a.area_id=l.area_id  WHERE dc.card_uid='$vehicle_tag' and c.status=1 and date('$date_to')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$date_from') ORDER BY dc.device_date_time DESC")->result();
	}
	public function report_plant_in()
	{
		$date_to=$this->input->post('date_to');
		return $this->db->query("SELECT v.vehicle_number,l.location_name,l.location_type,dc.device_date_time,TIMEDIFF(dc.in_out_plant_time,dc.device_date_time) as diff,dc.in_out_plant_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid WHERE l.location_type='i' and date('$date_to')=date(dc.device_date_time) ORDER BY dc.device_date_time DESC")->result();
	}
	public function report_plant_out()
	{
		$date_to=$this->input->post('date_to');
		return $this->db->query("SELECT v.vehicle_number,l.location_name,l.location_type,dc.device_date_time,TIMEDIFF(dc.device_date_time,dc.in_out_plant_time) as diff,dc.in_out_plant_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid WHERE l.location_type='o' and date('$date_to')=date(dc.device_date_time) ORDER BY dc.device_date_time DESC")->result();
	}
	public function total_count()
	{
		$date_to=$this->input->post('date_to');
		$total_count_in=$this->db->query("SELECT v.vehicle_number,l.location_name,l.location_type,dc.device_date_time,TIMEDIFF(dc.in_out_plant_time,dc.device_date_time) as diff,dc.in_out_plant_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid WHERE l.location_type='i' and date('$date_to')=date(dc.device_date_time) ORDER BY dc.device_date_time DESC")->result();

		$in_count=count($total_count_in);
		$in_x=0;
		for($i=0;$i<$in_count;$i++)
		{
			if(empty($total_count_in[$i]->in_out_plant_time))
			{
				++$in_x;
			}	
		}	

		$total_count_out=$this->db->query("SELECT v.vehicle_number,l.location_name,l.location_type,dc.device_date_time,TIMEDIFF(dc.device_date_time,dc.in_out_plant_time) as diff,dc.in_out_plant_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid WHERE l.location_type='o' and date('$date_to')=date(dc.device_date_time) ORDER BY dc.device_date_time DESC")->result();

		$out_count=count($total_count_out);
		$out_x=0;
		for($i=0;$i<$out_count;$i++)
		{
			if(empty($total_count_out[$i]->in_out_plant_time))
			{
				++$out_x;
			}	
		}

		$plant_in_location_name=($total_count_in[0]->location_name)?ucwords($total_count_in[0]->location_name):"Not Found";
		$plant_in_location_type=($total_count_in[0]->location_type)?ucwords($total_count_in[0]->location_type):"Not Found";
		$plant_out_location_name=($total_count_out[0]->location_name)?ucwords($total_count_out[0]->location_name):"Not Found";
		$plant_out_location_type=($total_count_out[0]->location_type)?ucwords($total_count_out[0]->location_type):"Not Found";
		return array(
			"plant_in_location_name"=>$plant_in_location_name,
			"plant_in_location_type"=>$plant_in_location_type,
			"in_count"=>$in_count,
			"in_x"=>$in_x,
			"plant_out_location_name"=>$plant_out_location_name,
			"plant_out_location_type"=>$plant_out_location_type,
			"out_count"=>$out_count,
			"out_x"=>$out_x
			);
	}
	public function get_all_active_device_and_vehicle_details()
	{
		return array(
			'active_device'=>$this->db->select('device_id,device_id_number,device_name')->where(['status'=>1,'connect'=>0])->get('device_table')->result(),
			'active_location'=>$this->db->query("SELECT  a.area_name,l.location_id,l.location_name,l.location_type FROM locations as l INNER JOIN area as a ON a.area_id=l.area_id where l.status=1 and connect_status=0 ORDER BY a.area_name ASC")->result(),
			'currently_connected'=>$this->db->query("SELECT c.connect_table_id,d.device_id,d.device_name,d.device_id_number,l.location_id,l.location_name,l.location_type,a.area_name,c.created_dt FROM locations as l , device_table as d, connect_table as c,area as a WHERE d.device_id=c.device_id AND l.location_id=c.location_id and a.area_id=l.area_id  AND c.status=1 order by c.created_dt DESC")->result(),
			);
	}
	public function get_all_connected_location()
	{
		return $this->db->query("SELECT DISTINCT(c.location_id) as location_id,l.location_name,l.connect_status FROM connect_table as c, locations as l WHERE c.location_id=l.location_id  ORDER by c.location_id ASC")->result();
	}
	
	public function get_track_of_vehicle($id)
	{
		return $this->db->query("SELECT c.*, CONCAT(cb.first_name,' ',cb.last_name) as created_by_full_name , CONCAT(mb.first_name,' ',mb.last_name) as modified_by_full_name,d.device_name,d.device_id_number,l.location_name FROM connect_table as c LEFT JOIN locations as l on l.location_id=c.location_id LEFT JOIN device_table as d ON d.device_id=c.device_id LEFT JOIN user_management as cb ON c.created_by=cb.user_id LEFT JOIN user_management as mb  ON c.modified_by=mb.user_id WHERE l.location_id=$id ORDER BY c.device_date_time DESC")->result();
	}
	public function get_all_locations()
	{
		return $this->db->query("SELECT l.*,a.area_name FROM locations as l INNER JOIN area as a ON a.area_id=l.area_id ORDER BY a.area_name ASC")->result();
	}
	public function get_single_location($id)
	{
		return $this->db->query("SELECT a.area_name,a.area_id,l.*, CONCAT(cb.first_name,' ',cb.last_name) as created_by_full_name , CONCAT(mb.first_name,' ',mb.last_name) as modified_by_full_name FROM locations as l LEFT JOIN user_management as cb ON l.created_by=cb.user_id LEFT JOIN user_management as mb  ON l.modified_by=mb.user_id INNER JOIN area as a ON a.area_id=l.area_id WHERE l.location_id=$id")->row();
	}
	
	public function add_management_user_model()
	{
		$first_name=stripcslashes(htmlspecialchars(strtolower(trim($this->input->post('first_name')))));
		$last_name=stripcslashes(htmlspecialchars(strtolower(trim($this->input->post('last_name')))));
		$phone_no=stripcslashes(htmlspecialchars(strtolower(trim($this->input->post('phone_no')))));
		$code_email_id=strtolower(stripcslashes(htmlspecialchars(trim($this->input->post('email_id')))));
		$email_id=stripcslashes(htmlspecialchars(trim($this->input->post('email_id'))));
		$password=stripcslashes(htmlspecialchars(trim($this->input->post('password'))));
		$salt=sha1('abcdefghijklmn');
		$password=$password.$salt;
		$user_password=sha1($password);

		$query=$this->db->where(['code_email_id'=>$code_email_id])->get('user_management');
		if($query->num_rows()==1)
		{
			$this->session->set_flashdata('succ_msg',ucfirst($email_id).' Email Id Already Added');
			$this->session->set_flashdata('color_msg',"warning");
			redirect('Vehicle_controller/add_management_user');
		}
		else
		{
			$data=array(
					'first_name'=>$first_name,
					'last_name'=>$last_name,
					'phone_no'=>$phone_no,
					'email_id'=>$email_id,
					'code_email_id'=>$code_email_id,
					'pass'=>$user_password,
					'user_type'=>$this->input->post('user_type'),
					'phone_no'=>$phone_no,
					'status'=>1,
					'created_by'=>$this->session->userdata('user_id'),
					'created_dt'=>$this->get_time()
					);
		$this->db->trans_begin();
		$this->db->insert('user_management',$data);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg',ucfirst($email_id).' User log Id Added Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/add_management_user');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('succ_msg','Database Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/add_management_user');
		}
	  }
	}
	public function reset_password_model()
	{
			$password=stripcslashes(htmlentities(trim($this->input->post('new_password'))));
			$user_id=$this->input->post('user_id');
			$email_id=$this->db->query("SELECT email_id FROM user_management WHERE user_id=$user_id")->row()->email_id;
			
			$salt=sha1('abcdefghijklmn');
			$password=$password.$salt;
			$password=sha1($password);

			$this->db->trans_begin();
			$this->db->where(['user_id'=>$user_id])->update('user_management',['pass'=>$password]);
			if ($this->db->affected_rows())
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('succ_msg',"Password Change Successfully of User Id : ".$email_id);
				$this->session->set_flashdata('color_msg','success');
				redirect('Vehicle_controller/view_management_user');
			}
			else
			{  
				$this->db->trans_rollback();
				$this->session->set_flashdata('succ_msg',"Nothing has been Update for Change Password");
				$this->session->set_flashdata('color_msg','danger');
				redirect('Vehicle_controller/view_management_user');
			}
	}
	public function edit_user_account_detail_model()
	{	
		
		$email_id=stripcslashes(htmlspecialchars(trim($this->input->post('email_id'))));
		$update_data=array(
					'first_name'=>stripcslashes(htmlspecialchars(strtolower(trim($this->input->post('first_name'))))),
					'last_name'=>stripcslashes(htmlspecialchars(strtolower(trim($this->input->post('last_name'))))),
					'phone_no'=>stripcslashes(htmlspecialchars(strtolower(trim($this->input->post('phone_no'))))),
					'code_email_id'=>strtolower(stripcslashes(htmlspecialchars(trim($this->input->post('email_id'))))),
					'email_id'=>$email_id,
					'user_type'=>$this->input->post('user_type'),
					'status'=>$this->input->post('status'),
					'modified_by'=>$this->session->userdata('user_id'),
					'modified_dt'=>$this->get_time()
					);
		$this->db->trans_begin();
		$this->db->where(['user_id'=>$this->input->post('user_id')])->update('user_management',$update_data);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg',ucfirst($email_id).' Updated Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/view_management_user');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('succ_msg','Database Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/view_management_user');
		}
	}

	public function update_admin_profile()
	{
		$first_name=stripslashes(htmlentities(trim($this->input->post('first_name'))));
		$last_name=stripslashes(htmlentities(trim($this->input->post('last_name'))));
		$phone_no=stripslashes(htmlentities(trim($this->input->post('phone_no'))));

		$update_data=array(
			'first_name'=>$first_name,
			'last_name'=>$last_name,
			'phone_no'=>$phone_no
			);

		$management_user_data = array('user_name'  => $result->first_name.' '.$result->last_name);
		$this->session->set_userdata($management_user_data);
		
		$this->db->trans_begin();
		$this->db->where(['user_id'=>$this->session->userdata('user_id')])->update('user_management',$update_data);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg','Profile Updated Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/my_profile');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('succ_msg','Database Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/my_profile');
		}

	}
	public function change_password_admin()
	{
		$password=stripslashes(htmlspecialchars(trim($this->input->post('old_password'))));
		$salt=sha1('abcdefghijklmn');
		$password=$password.$salt;
		$password=sha1($password);
		$query=$this->db->where(['user_id'=>$this->session->userdata('user_id'),'pass'=>$password])->get('user_management');
		if($query->num_rows()>0)
		{
			
			$password=stripslashes(htmlspecialchars(trim($this->input->post('new_password'))));
			$salt=sha1('abcdefghijklmn');
			$password=$password.$salt;
			$password=sha1($password);

			$this->db->trans_begin();
			$this->db->where(['user_id'=>$this->session->userdata('user_id')])->update('user_management',['pass'=>$password]);
			if ($this->db->affected_rows())
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('succ_msg',"Password Changed Successfully");
				$this->session->set_flashdata('color_msg','success');
				redirect('Vehicle_controller/change_password');
			}
			else
			{  
				$this->db->trans_rollback();
				$this->session->set_flashdata('succ_msg',"Nothing has been Update for Change Password");
				$this->session->set_flashdata('color_msg','danger');
				redirect('Vehicle_controller/change_password');
			}
		}
		else
		{
			$this->session->set_flashdata('succ_msg',"Old Password Do not Match");
			$this->session->set_flashdata('color_msg','danger');
			redirect('Vehicle_controller/change_password');
		}	
	}
	public function add_area_model()
	{

		$area_name=stripcslashes(htmlspecialchars(trim($this->input->post('area_name'))));
		$query=$this->db->where(['area_name'=>strtolower($area_name)])->get('area');
		if($query->num_rows()==1)
		{
			$this->session->set_flashdata('succ_msg',$area_name.' Area Already Added');
			$this->session->set_flashdata('color_msg',"warning");
			redirect('Vehicle_controller/add_area');
		}
		else
		{
			$data=array(
					'area_name'=>strtolower($area_name),
					'status'=>1,
					'created_by'=>$this->session->userdata('user_id'),
					'created_dt'=>$this->get_time()
					);
		
				$this->db->trans_begin();
				$this->db->insert('area',$data);
				if ($this->db->affected_rows())
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('succ_msg',$area_name.' Area Added Successfully');
					$this->session->set_flashdata('color_msg',"success");
					redirect('Vehicle_controller/add_area');
				}
				else
				{  
					$this->db->trans_rollback();
					$this->session->set_flashdata('err_msg','DataBase Problem');
					$this->session->set_flashdata('color_msg',"danger");
					redirect('Vehicle_controller/add_area');
				}
		}	
	}
	public function edit_area_detail_model()
	{
		$area_name=stripcslashes(htmlspecialchars(trim($this->input->post('area_name'))));
		$update_data=array(
				'area_name'=>strtolower($area_name),
				'status'=>$this->input->post('status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'modified_dt'=>$this->get_time()
				);

			$this->db->trans_begin();
			$this->db->where(['area_id'=>$this->input->post('area_id')])->update('area',$update_data);
			if ($this->db->affected_rows())
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('succ_msg',$area_name.' Area Updated Successfully');
				$this->session->set_flashdata('color_msg',"success");
				redirect('Vehicle_controller/view_area');
			}
			else
			{  
				$this->db->trans_rollback();
				$this->session->set_flashdata('err_msg','DataBase Problem');
				$this->session->set_flashdata('color_msg',"danger");
				redirect('Vehicle_controller/view_area');
			}
	}
	public function add_vehicle_model()
	{
		$vehicle_number=stripcslashes(htmlspecialchars(trim($this->input->post('vehicle_number'))));
		$query=$this->db->where(['vehicle_number'=>$vehicle_number])->get('vehicle');
		if($query->num_rows()==1)
		{
			$this->session->set_flashdata('succ_msg',$vehicle_number.' Vehicle Number Already Added');
			$this->session->set_flashdata('color_msg',"warning");
			redirect('Vehicle_controller/add_vehicle');
		}
		else
		{
			$data=array(
					'vehicle_number'=>$vehicle_number,
					'vehicle_tag'=>stripcslashes(htmlspecialchars(trim($this->input->post('vehicle_tag')))),
					'status'=>1,
					'created_by'=>$this->session->userdata('user_id'),
					'created_dt'=>$this->get_time()
					);
		
				$this->db->trans_begin();
				$this->db->insert('vehicle',$data);
				if ($this->db->affected_rows())
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('succ_msg',$vehicle_number.' vehicle Number Added Successfully');
					$this->session->set_flashdata('color_msg',"success");
					redirect('Vehicle_controller/add_vehicle');
				}
				else
				{  
					$this->db->trans_rollback();
					$this->session->set_flashdata('err_msg','DataBase Problem');
					$this->session->set_flashdata('color_msg',"danger");
					redirect('Vehicle_controller/add_vehicle');
				}
		}	
	}
	public function search_vehicle_model()
	{
		$vehicle=strtoupper(stripcslashes(htmlspecialchars(trim($this->input->post('vehicle_tag')))));
		$query=$this->db->query("SELECT * FROM vehicle WHERE vehicle_number='$vehicle' or vehicle_tag='$vehicle'")->result();
		return $query;
	}
	 public function edit_user_vehicle_detail_model()
	 {
	 	$vehicle_number=stripcslashes(htmlspecialchars(trim($this->input->post('vehicle_number'))));
		$update_data=array(
				'vehicle_number'=>$vehicle_number,
				'vehicle_tag'=>stripcslashes(htmlspecialchars(trim($this->input->post('vehicle_tag')))),
				'status'=>$this->input->post('status'),
				'modified_by'=>$this->session->userdata('user_id'),
				'modified_dt'=>$this->get_time()
				);

			$this->db->trans_begin();
			$this->db->where(['vehicle_id'=>$this->input->post('vehicle_id')])->update('vehicle',$update_data);
			if ($this->db->affected_rows())
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('succ_msg',$vehicle_number.' Vehicle Updated Successfully');
				$this->session->set_flashdata('color_msg',"success");
				redirect('Vehicle_controller/view_all_vehicle');
			}
			else
			{  
				$this->db->trans_rollback();
				$this->session->set_flashdata('err_msg','DataBase Problem');
				$this->session->set_flashdata('color_msg',"danger");
				redirect('Vehicle_controller/view_all_vehicle');
			}
	}
	public function add_device_model()
	{
		$device_id_number=stripcslashes(htmlspecialchars(trim($this->input->post('device_id_number'))));
		$code_device_id_number=strtolower(stripcslashes(htmlspecialchars(trim($this->input->post('device_id_number')))));
		
		$query=$this->db->where(['code_device_id_number'=>$code_device_id_number])->get('device_table');
		if($query->num_rows()==1)
		{
			$this->session->set_flashdata('succ_msg',$code_device_id_number.' Device Id Already Added');
			$this->session->set_flashdata('color_msg',"warning");
			redirect('Vehicle_controller/add_device');
		}
		else
		{
			$data=array(
					'device_name'=>stripcslashes(htmlspecialchars(trim($this->input->post('device_name')))),
					'device_id_number'=>$device_id_number,
					'code_device_id_number'=>$code_device_id_number,
					'status'=>1,
					'created_by'=>$this->session->userdata('user_id'),
					'created_dt'=>$this->get_time()
					);
		$this->db->trans_begin();
		$this->db->insert('device_table',$data);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg',$code_device_id_number.' Device Added Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/add_device');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('succ_msg','Database Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/add_device');
		}
	  }
	}
	public function edit_location_detail_model()
	{
		$location_name=stripcslashes(htmlspecialchars(trim($this->input->post('location_name'))));
		$update_data=array(
			'area_id'=>$this->input->post('area_id'),
			'location_name'=>strtolower(stripcslashes(htmlspecialchars(trim($this->input->post('location_name'))))),
			'location_type'=>$this->input->post('location_type'),
			'status'=>$this->input->post('status'),
			'modified_by'=>$this->session->userdata('user_id'),
			'modified_dt'=>$this->get_time()
			);

		$this->db->trans_begin();
		$this->db->where(['location_id'=>$this->input->post('location_id')])->update('locations',$update_data);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg',$location_name.' Location Updated Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/view_location');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('err_msg','DataBase Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/view_location');
		}
	}
	public function add_location_model()
	{
		$area_id=$this->input->post('area_id');
		$location_type=$this->input->post('location_type');
		$location_name=stripcslashes(htmlspecialchars(trim($this->input->post('location_name'))));
		$query=$this->db->where(['location_name'=>strtolower($location_name),'area_id'=>$area_id,'location_type'=>$location_type])->get('locations');
		if($query->num_rows()==1)
		{
			$this->session->set_flashdata('succ_msg',$location_name.' Location Already Added');
			$this->session->set_flashdata('color_msg',"warning");
			redirect('Vehicle_controller/add_location');
		}
		else
		{
			$data=array(
					'area_id'=>$area_id,
					'location_name'=>strtolower($location_name),
					'location_type'=>$this->input->post('location_type'),
					'status'=>1,
					'connect_status'=>0,
					'created_by'=>$this->session->userdata('user_id'),
					'created_dt'=>$this->get_time()
					);
		$this->db->trans_begin();
		$this->db->insert('locations',$data);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg',$location_name.' Location Added Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/add_location');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('succ_msg','Database Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/add_location');
		}
	  }
	}
	public function edit_device_detail_model()
	{
	 	$device_id_number=stripcslashes(htmlspecialchars(trim($this->input->post('device_id_number'))));
		$code_device_id_number=strtolower(stripcslashes(htmlspecialchars(trim($this->input->post('device_id_number')))));
		
		$update_data=array(
					'device_name'=>stripcslashes(htmlspecialchars(trim($this->input->post('device_name')))),
					'device_id_number'=>$device_id_number,
					'code_device_id_number'=>$code_device_id_number,
					'status'=>$this->input->post('status'),
					'modified_by'=>$this->session->userdata('user_id'),
					'modified_dt'=>$this->get_time()
					);
		
				$this->db->trans_begin();
				$this->db->where(['device_id'=>$this->input->post('device_id')])->update('device_table',$update_data);
				if ($this->db->affected_rows())
				{
					$this->db->trans_commit();
					$this->session->set_flashdata('succ_msg',$device_id_number.' Device Id Updated Successfully');
					$this->session->set_flashdata('color_msg',"success");
					redirect('Vehicle_controller/view_device');
				}
				else
				{  
					$this->db->trans_rollback();
					$this->session->set_flashdata('err_msg','DataBase Problem');
					$this->session->set_flashdata('color_msg',"danger");
					redirect('Vehicle_controller/view_device');
				}
	}
	
	public function connect_device_model()
	{
		$device_id=$this->input->post('device_id');
		$device_details=$this->db->select('device_name,device_id_number')->where(['device_id'=>$device_id])->get('device_table')->row();
		$device_name=$device_details->device_name;
		$device_id_number=$device_details->device_id_number;

		$location_id=$this->input->post('location_id');
		$location_details=$this->db->select('location_name,location_type')->where(['location_id'=>$location_id])->get('locations')->row();
		$location_name=ucwords($location_details->location_name);
		$location_type=$location_details->location_type;	
		

		$insert_data=array(
			'device_id'=>$device_id,
			'location_id'=>$location_id,
			'status'=>1,
			'created_by'=>$this->session->userdata('user_id'),
			'created_dt'=>$this->get_time()
			);
		$this->db->trans_begin();
		$this->db->insert('connect_table',$insert_data);
		$this->db->where(['device_id'=>$this->input->post('device_id')])->update('device_table',['connect'=>1]);
		$this->db->where(['location_id'=>$this->input->post('location_id')])->update('locations',['connect_status'=>1]);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg',"$device_id_number ($device_name) Connected with $location_name Successfully");
			$this->session->set_flashdata('color_msg','success');
			redirect('Vehicle_controller/connect_device');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('err_msg','DataBase Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/connect_device');
		}
	}	
	public function remove_connect_device_model($id,$device_id,$location_id)
	{
		$update_data=array(
			'status'=>0,
			'modified_by'=>$this->session->userdata('user_id'),
			'modified_dt'=>$this->get_time()
			);
		$this->db->trans_begin();
		$this->db->where(['connect_table_id'=>$id])->update('connect_table',$update_data);
		$this->db->where(['device_id'=>$device_id])->update('device_table',['connect'=>0]);
		$this->db->where(['location_id'=>$location_id])->update('locations',['connect_status'=>0]);
		if ($this->db->affected_rows())
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('succ_msg','Connection Removed Successfully');
			$this->session->set_flashdata('color_msg',"success");
			redirect('Vehicle_controller/connect_device');
		}
		else
		{  
			$this->db->trans_rollback();
			$this->session->set_flashdata('err_msg','DataBase Problem');
			$this->session->set_flashdata('color_msg',"danger");
			redirect('Vehicle_controller/connect_device');
		}
	}
	public function get_device_status()
	{
		$query=$this->db->query("SELECT d.device_id,d.device_name,d.device_id_number,dc.created_dt,l.location_name,a.area_name,l.location_type FROM device_table as d INNER JOIN connect_table as ct ON ct.device_id=d.device_id INNER JOIN locations as l ON l.location_id=ct.location_id INNER JOIN area as a ON l.area_id=a.area_id LEFT JOIN data_cl03 as dc ON dc.imei_no=d.device_id_number WHERE ct.status=1 ORDER BY a.area_name ASC")->result();

		$x=array();
		$total_count=0;
		$active_count=0;
		foreach($query as $query)
		{
			$total_count=++$total_count;
			$data['device_name']=$query->device_name;
			$data['device_id_number']=$query->device_id_number;
			$data['location_name']=ucwords($query->location_name);
			$data['location_type']=($query->location_type=='i')?"In":"Out";
			$data['area_name']=ucwords($query->area_name);
			
			$get_time=$this->get_time();
			$time1 = strtotime($get_time);
			$time2 = strtotime($query->created_dt);
			$difference = round(abs($time2 - $time1) / 60,2);
			$y=0;
			if($difference>5)
			{
				$data['status']="OFF";
			}
			else
			{
				$data['status']="ON";
				$active_count=++$active_count;

			}

			array_push($x,$data);	

		}
		$inactive_count=$total_count-$active_count;
		return array("data"=>$x,"total_count"=>$total_count,"active_count"=>$active_count,"inactive_count"=>$inactive_count);
		
	}
	public function get_live_count()
	{
		$get_time=date('Y-m-d');

		$in=$this->db->query("SELECT COUNT(dc.id) as in_count FROM data_cl06 as dc INNER JOIN locations as l ON l.location_id=dc.location_id WHERE l.location_type='i' and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time')")->row()->in_count;
		$out=$this->db->query("SELECT COUNT(dc.id) as out_count FROM data_cl06 as dc INNER JOIN locations as l ON l.location_id=dc.location_id WHERE l.location_type='o' and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time')")->row()->out_count;
		return array("in"=>$in,'out'=>$out);
	}
	public function live_transaction_in()
	{
		$get_time=date('Y-m-d');

		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id  WHERE l.location_type='i' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time DESC limit 15")->result();
	}

	public function in_vehicle_transaction_rows_model()
	{
		$get_time=date('Y-m-d');
		return $this->db->query("SELECT count(v.vehicle_tag) as count FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id  WHERE l.location_type='i' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time  DESC")->row()->count;
	}
	public function in_vehicle_transaction_report_model($limit,$offset)
	{
		$get_time=date('Y-m-d');
		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id  WHERE l.location_type='i' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time DESC limit $limit OFFSET $offset ")->result();
	}
	public function out_vehicle_transaction_rows_model()
	{
		$get_time=date('Y-m-d');
		return $this->db->query("SELECT count(v.vehicle_tag) as count FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id  WHERE l.location_type='o' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time")->row()->count;
	}
	public function out_vehicle_transaction_report_model($limit,$offset)
	{
		$get_time=date('Y-m-d');
		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_name,d.device_id_number,l.location_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id  WHERE l.location_type='o' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time DESC limit $limit OFFSET $offset")->result();
	}


	public function in_devices_rows()
	{
		$get_time=date('Y-m-d');
		return $this->db->query("SELECT count(v.vehicle_tag) as count FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id  WHERE l.location_type='i' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time")->result()->count;
	}
	public function live_transaction_out()
	{
		$get_time=date('Y-m-d');
		return $this->db->query("SELECT v.vehicle_tag,v.vehicle_number,d.device_id_number,l.location_name,dc.device_date_time FROM data_cl06 as dc INNER JOIN locations as l on l.location_id=dc.location_id INNER JOIN vehicle as v ON v.vehicle_tag=dc.card_uid INNER JOIN connect_table as c ON c.location_id=l.location_id INNER JOIN device_table as d ON d.device_id=c.device_id WHERE l.location_type='o' and c.status=1 and date('$get_time')<=date(dc.device_date_time) and date(dc.device_date_time)<=date('$get_time') ORDER BY dc.device_date_time DESC limit 15")->result();
	}
	public function get_live_tag()
	{
		return $this->db->query('SELECT COUNT(vehicle_id) as count FROM vehicle')->result()[0]->count;
	}
}	