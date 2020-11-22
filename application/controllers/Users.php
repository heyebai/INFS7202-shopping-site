<?php
class Users extends CI_Controller {
	public function register() {
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required|max_length[20]|min_length[2]');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|max_length[20]|min_length[2]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[50]|min_length[5]|valid_email');
		$this->form_validation->set_rules('question', 'Secret question', 'trim|required');
		$this->form_validation->set_rules('answer', 'Answer of the secret question', 'trim|required');
		$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[20]|min_length[6]');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[20]|min_length[6]');

		if ($this->form_validation->run() == FALSE) {
			$data['main_content'] = 'user/login';
			$this->load->view('home',$data);
		} else {
//			if ($this->User_model->new_account()) {
			$this->User_model->new_account();
			if ($this->session->flashdata('registered_fail')) {
			} else {
				$this->session->set_flashdata('registered','You are now registered. Please activate your account in your email.');
				redirect('home/index');
			}

//			}
		}
	}

	public function validation_email($email, $email_code) {
		$user = $this->User_model->check_email($email);
		$email_code_true = md5($user->register_time);
		if ($email_code_true == $email_code) {
			$this->User_model->activate_account($email);
			$this->session->set_flashdata('activate_success','Your account has been activated.');
			redirect('home/index');
		}
	}

	public function login () {

		$this->form_validation->set_rules('username','Username','trim|required|max_length[20]|min_length[6]');
		$this->form_validation->set_rules('password', 'Password','trim|required|max_length[20]|min_length[6]');
		if ($this->form_validation->run() == FALSE) {
			$data['main_content'] = 'user/login';
			$this->load->view('home',$data);
		} else {
			if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
				$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6Lcta_wUAAAAANS9GbGcX2DkGTmAQ8ySwxtE2Cu_&response='.$_POST['g-recaptcha-response']);
				$responseData = json_decode($verifyResponse);
				if ($responseData->success) {
					$username = $this->input->post('username');
					$password = $this->input->post('password');

					$user = $this->User_model->login($username, $password);

					if ($user) {
						if ($user->active == 0) {
							$this->session->set_flashdata('activate_failed','Sorry, the account is not active.');
							redirect('home/index');
						} else {
							if (!empty($_POST['remember'])) {
								set_cookie('username', $username,  120);
								set_cookie('password', $password,  120);
							} else {
								set_cookie('username', $username,  -1);
								set_cookie('password', $password, -1);
							}
							$user_data = array(
								'username' => $username,
								'logged_in' => true,
								'first_name' => $user->first_name,
								'last_name' => $user->last_name,
								'email' => $user->email
							);
							$this->session->set_userdata($user_data);
							$this->session->set_flashdata('login_success','You have logged in.');
							redirect('home/index');
						}
					} else {
						$this->session->set_flashdata('login_failed','Sorry, the account is invalid.');
						redirect('home/index');
					}
				} else {
					echo "Robot verification failed, please try again.";
				}
			} else {
				echo "Please check on the reCAPTCHA box.";
			}
		}
	}

	public function forgot() {
        $data['main_content'] = 'user/forgot';
        $this->load->view('home',$data);
    }

    public function check_email() {
        $email = $this->input->post('check_email');
	    $result = $this->User_model->check_email($email);
	    if ($result) {
            $data = array(
                'main_content' => 'user/answer_question',
                'user_info' => $result
            );
            $this->load->view('home',$data);
        } else {
            $this->session->set_flashdata('email_not_found','Sorry, the email is invalid.');
            redirect('users/forgot');
        }
    }

    public function check_answer() {
        $email = $this->input->post('email');
        $result = $this->User_model->check_email($email);

        $answer = $this->input->post('check_answer');
        if ($result->answer == $answer) {
            $data = array(
                'main_content' => 'user/set_password',
                'user_info' => $result
            );
            $this->load->view('home',$data);
        } else {
            $this->session->set_flashdata('wrong_answer','Sorry, the answer is wrong.');
            $data = array(
                'main_content' => 'user/answer_question',
                'user_info' => $result
            );
            $this->load->view('home',$data);
        }
    }
    
    public function set_password() {
        $email = $this->input->post('email');
        
        $new_password =$this->input->post('new_password');
        $this->User_model->set_password($email, $new_password);
        $this->session->set_flashdata('set_password_success','You have set your new password.');
        $data['main_content'] = 'user/login';
        $this->load->view('home',$data);
    }

	public function logout() {
		$this->session->unset_userdata('username');
		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('first_name');
		$this->session->unset_userdata('last_name');
		$this->session->unset_userdata('email');
		$this->session->set_flashdata('logged_out', 'You have been logged out.');

		redirect('home/index');
	}

	public function profile() {
		$data['main_content'] = 'user/profile';
		$this->load->view('home',$data);
	}

	public function update() {
		$this->form_validation->set_rules('email_u', 'Email', 'trim|required|max_length[50]|min_length[5]|valid_email');

		if ($this->form_validation->run() == FALSE) {
			$data['main_content'] = 'user/profile';
			$this->load->view('home', $data);
		} else {
			$this->User_model->update_m();
			if ($this->session->flashdata('update_fail')) {
			} else {
				$this->session->set_flashdata('updated','Update success.');
				redirect('users/profile');
			}
		}
	}

	public function upload() {
		$config['upload_path'] = './assets/images/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload("image")) {
			$this->session->set_flashdata('upload_failed','Uploaded failed.');
			redirect('users/profile');
		} else {
			//$image_name = basename($_FILES['image']['name']);
			$this->User_model->upload_image($this->upload->data('file_name'));
			//$this->upload->do_upload('image');

			$this->session->set_flashdata('upload_success','Uploaded successfully.');
			redirect('users/profile');
		}
	}

	public function drop_upload() {

//		if(!empty($_FILES['image']['name']) && !empty($_POST['email'])){

			foreach($_FILES['image']['name'] as $index => $name){
				move_uploaded_file($_FILES['image']['tmp_name'][$index], './assets/images/'.$name);
				$this->User_model->upload_image($name);
				$uploaded[] = array(
					'name' => $name,
					'file' => './assets/images/'.$name
				);
			}

		echo json_encode($uploaded);
//		}
//		move_uploaded_file($_FILES['image']['tmp_name'], './assets/images/'.$_FILES['image']["name"]);

	}

	public function watermark() {
		$image_info = $this->User_model->check_image();
		$config['source_image'] = './assets/images/'.$image_info->image;
		$config['wm_text'] = '7';
		$config['wm_type'] = 'text';
		$config['wm_font_path'] = './system/fonts/texb.ttf';
		$config['wm_font_size'] = '128';
		$config['wm_font_color'] = 'ffffff';
		$config['wm_vrt_alignment'] = 'middle';
		$config['wm_hor_alignment'] = 'center';
		$config['wm_padding'] = '20';
		$this->image_lib->initialize($config);
		$this->image_lib->watermark();
		$this->session->set_flashdata('watermark_success','Watermark added successfully.');
		redirect('users/profile');
	}

	public function sell() {
		$data['main_content'] = 'user/sell';
		$this->load->view('home',$data);
	}

	public function createProduct() {
		$num = count($_FILES['product_img']['name']);
		$this->load->library('upload');
		$files = $_FILES;

		$title = $this->input->post('title');
		$price = $this->input->post('price');
		$description = $this->input->post('description');

		for ($i = 0; $i < $num; $i++) {
			$_FILES['product_img']['name'] = $files['product_img']['name'][$i];
			$_FILES['product_img']['type'] = $files['product_img']['type'][$i];
			$_FILES['product_img']['tmp_name'] = $files['product_img']['tmp_name'][$i];
			$_FILES['product_img']['error'] = $files['product_img']['error'][$i];
			$_FILES['product_img']['size'] = $files['product_img']['size'][$i];

			$config['upload_path'] = './assets/images/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->upload->initialize($config);

			$img_name = $files['product_img']['name'][$i];

			if ( ! $this->upload->do_upload("product_img")) {
				$this->session->set_flashdata('upload_failed','Uploaded failed.');
				redirect('users/sell');
			} else {
				if ($i == 0) {
					$this->User_model->product_info_upload($title, $price, $img_name, $description);
				} else {
					$this->User_model->multi_img_upload($i, $img_name);
				}
			}
		}
		$this->session->set_flashdata('upload_success','Uploaded successfully.');
		redirect('users/sell');
	}
	
	public function autocompletion() {
		$output = '';
		$query = '';
		if($this->input->post('query')){
			$query = $this->input->post('query');
		}
		$data = $this->User_model->autocompletion_data($query);
		if($data->num_rows() > 0){
			echo json_encode ($data->result());
		}else{
			$output .= 'No Data Found';
		}
		echo $output;
	}
	
	public function load_details($id) {
		if (isset($_POST['cart'])) {
			$this->User_model->upload_info_to_cart($id);
			$this->session->set_flashdata('add_success','Added successfully.');
		}

		$product = $this->User_model->search_product($id);
		$images = array();
		for ($i = 0; $i < 6; $i++) {
			$image = 'image'.$i;
			array_push($images, $product->$image);
		}
		$data = array(
			'id' => $id,
			'images' => $images,
			'price' => $product->price,
			'title' => $product->title,
			'description' => $product->description,
			'main_content' => 'product/details'
		);

		$this->load->view('home',$data);


	}
	
	public function load_reviews($id) {

		$output = '';
		$reviews = $this->User_model->search_reviews($id);
		if($reviews->num_rows() > 0){
			echo json_encode ($reviews->result());
		}else{
			$output .= 'No reviews';
		}
		if ($this->input->post('query')) {
			$review = $this->input->post('query');
			$this->User_model->upload_review($review, $id);
		}
		echo $output;
	}

	public function cart() {
		if (isset($_SESSION['username'])) {
			$cart = $this->User_model->load_cart();
			$data = array(
				'cart' => $cart,
				'main_content' => 'user/cart'
			);

		} else {
			$data['main_content'] = 'user/cart';
		}
		$this->load->view('home',$data);
	}
	
	public function delete_item() {
		$id = $this->input->post('product_id');
		$this->User_model->delete_item($id);
		redirect('Users/cart');
	}
	
	public function chat() {
		$output = '';
//		$message = $this->input->post('message');
//		$user_from = $this->session->userdata('username');
		if ($this->input->post('query')) {
			$message = $this->input->post('query');
			$this->User_model->upload_chat($message);
		}
		$messages =  $this->User_model->search_chat();
		if ($messages->num_rows() > 0) {
			echo json_encode ($messages->result());
		} else {
			$output .= 'No Data';
		}
		echo $output;
	}
	
	


}
