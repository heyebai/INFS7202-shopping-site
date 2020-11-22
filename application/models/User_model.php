<?php

class User_model extends CI_Model {

	private $user_email;

	public function new_account() {
		$enc_password = $this->encryption->encrypt($this->input->post('password'));
		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$accounts = $this->db->get('account');
		foreach ($accounts->result() as $row) {
			if ($row->username == $username || $row->email == $email) {
				$this->session->set_flashdata('registered_fail','This username or email has been registered.');
				$data['main_content'] = 'user/login';
				$this->load->view('home',$data);
			}
		}
		if ($this->session->flashdata('registered_fail')) {
		} else {
			$data = array(
				'first_name' => $this->input->post('firstname'),
				'last_name' => $this->input->post('lastname'),
				'email' => $this->input->post('email'),
                'question' => $this->input->post('question'),
                'answer' => $this->input->post('answer'),
				'username' => $this->input->post('username'),
				'password' => $enc_password
			);
			$this->db->insert('account', $data);
			$this->user_email = $this->input->post('email');
			$this->send_validation_email();
		}

		//return $query;
	}

	public function login($username, $password) {

		$this->db->where('username', $username);
		$result = $this->db->get('account');
		$dec_password = $this->encryption->decrypt($result->row(0)->password);
		if ($dec_password == $password) {
			return $result->row(0);
		} else {
			return false;
		}
	}

	public function update_m() {
		$email = $this->input->post('email_u');
		$accounts = $this->db->get('account');
		foreach ($accounts->result() as $row) {
			if ($row->email == $email) {
				$this->session->set_flashdata('update_fail','This email has been registered.');
				redirect('users/profile');
			}
		}

		if ($this->session->flashdata('update_fail')) {
		} else {
			$data = array(
				'email' => $email
			);
			$username = $this->session->userdata('username');
			$this->db->where('username', $username);
			$this->db->update('account', $data);
			$this->session->set_userdata($data);
		}
	}

	public function upload_image($image_name) {
		$image_info = $this->check_image();
		if ($image_info) {
			$data = array(
				'image' => $image_name
			);
			$this->db->where('username', $image_info->username);
			$this->db->update('images', $data);
		} else {
			$data = array(
				'username' => $this->session->userdata('username'),
				'image' => $image_name
			);
			$this->db->insert('images', $data);
		}
	}

	public function check_image() {
		$this->db->where('username', $this->session->userdata('username'));
		$result = $this->db->get('images');
		if ($result->num_rows() == 1) {
			return $result->row(0);
		} else {
			return false;
		}
	}

	public function check_email($email) {
	    $this->db->where('email', $email);
	    $result = $this->db->get('account');
	    if ($result->num_rows() == 1) {
	        return $result->row(0);
        } else {
	        return false;
        }
    }

    public function set_password($email, $password) {
	    $enc_password = $this->encryption->encrypt($password);
        $data = array(
            'password' => $enc_password
        );
        $this->db->where('email', $email);
        $this->db->update('account', $data);
    }

    public function send_validation_email() {
		$user_info = $this->check_email($this->user_email);
		$email_code = md5((string)$user_info->register_time);

		$this->load->library('email');
		$config['protocol'] = 'smtp';
		$config['mailpath'] = '/usr/sbin/sendmail';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;
		$config['smtp_host'] = 'mailhub.eait.uq.edu.au';
		$config['smtp_port'] = 25;
		$config['mailtype'] = 'html';

		$this->email->initialize($config);
		$this->email->from('genuinehyb@yahoo.com', 'MyAuction');
		$this->email->to($this->user_email);

		$this->email->subject('Email Test');
		$this->email->subject('Please activate your account at MyAuction.');
		$message = '<!doctype html> <html> <head> <meta content="text/html">
					</head> <body>';
		$message .= '<p>Dear ' . $user_info->first_name . ',</p>';
		$message .= '<p>Thanks for registering on MyAuction! Please <strong><a href="'. base_url() .'users/validation_email/'. $this->user_email .
					'/'. $email_code .'">click here</a></strong> to active your account. After you have activate your account, you will be able to
					log into MyAuction!</p>';
		$message .= '<p>Thank you!</p>';
		$message .= '<p>The team at MyAuction.</p>';
		$message .= '</body></html>';

		$this->email->message($message);
		if (!$this->email->send()) {
			$this->session->set_flashdata('email_sent_fail',$this->email->print_debugger());
		}
	}

	public function activate_account($email) {
		$user = $this->check_email($email);
		$data = array(
			'active' => 1
		);
		$this->db->where('email', $email);
		$this->db->update('account', $data);

	}

	public function product_info_upload($title, $price, $img, $description) {
		$data = array(
			'username' => $this->session->userdata('username'),
			'image0' => $img,
			'title' => $title,
			'price' => $price,
			'description' => $description
		);
		$this->db->insert('products', $data);
	}

	public function multi_img_upload($index, $img) {
		$this->db->select('*');
		$this->db->from('products');
		$this->db->order_by('id', 'DESC');
		$query = $this->db->get();
		$row = $query->row(0);
		$this->db->set('image'.$index, $img);
		$this->db->where('id', $row->id);
		$this->db->update('products');
	}

	public function autocompletion_data($query) {
		$this->db->select("*");
		$this->db->from("products");
		if($query != '') {
			$this->db->like('title', $query);
			$this->db->or_like('description', $query);
		}
		$this->db->order_by('price', 'DESC');
		return $this->db->get();
	}

	public function search_product($id) {
		$this->db->where('id', $id);
		$result = $this->db->get('products');
		if ($result->num_rows() == 1) {
			return $result->row(0);
		} else {
			return false;
		}
	}

	public function search_reviews($id) {
		$this->db->where('product_id', $id);
		$this->db->order_by('time', 'DESC');
		$query = $this->db->get('reviews');
		return $query;
	}

	public function upload_review($review, $id) {
		
		if (isset($_SESSION['username'])) {
			$username = $this->session->userdata('username');
		} else {
			$username = $_SERVER['REMOTE_ADDR'];
		}
		$data = array(
			'username' => $username,
			'product_id' => $id,
			'review' => $review
		);
		$this->db->insert('reviews', $data);
	}

	public function upload_info_to_cart($id) {
		$data = array(
			'username' => $this->session->userdata('username'),
			'product_id' => $id
		);
		$this->db->insert('cart', $data);
	}

	public function load_cart() {
		$username = $this->session->userdata('username');
		$this->db->from('cart');
		$this->db->where('cart.username', $username);
		$this->db->join('products', 'cart.product_id = products.id');
		$this->db->order_by('cart.time', 'DESC');
		$query = $this->db->get();
		return $query->result();
	}

	public function delete_item($id) {
		$this->db->where('id_cart', $id);
		$this->db->delete('cart');
	}

	public function upload_chat($message) {
		$from = $this->session->userdata('username');
		$data = array(
			'message' => $message,
			'user_from' => $from
		);
		$this->db->insert('chat', $data);
	}

	public function search_chat() {
		$num = $this->count_chat();
		$this->db->from('chat');
		$this->db->order_by('time', 'ASC');
		if ($num > 5) {
			$this->db->limit(5,$num - 5);
		}
		$results = $this->db->get();

		return $results;
	}

	public function count_chat() {
		$this->db->from('chat');
		$query = $this->db->get();
		return $query->num_rows();
	}


}
