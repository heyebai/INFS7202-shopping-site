<?php
class Home extends CI_Controller {
	public function index () {
		$data['main_content'] = 'defaultView';
		$this->load->view('home',$data);
	}
	
}
