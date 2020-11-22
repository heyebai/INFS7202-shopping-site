<?php
class Papers extends CI_Controller {
	private $xixi;

	public function read ($year = 2018) {
		$this->xixi = 'hehe';
		echo $this->xixi;
	}

	public function index () {
		require __DIR__ . '/vendor/autoload.php';
		use Twilio\Rest\Client;

// Your Account SID and Auth Token from twilio.com/console
		$account_sid = 'ACd1a3a0cea0302698af60aa62e0672ed4';
		$auth_token = 'f250d73c3d7c065b2654dabe5e6d3c5a';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

// A Twilio number you own with SMS capabilities
		$twilio_number = "+19389991908";

		$client = new Client($account_sid, $auth_token);
		$client->messages->create(
		// Where to send a text message (your cell phone?)
			'+610404429617',
			array(
				'from' => $twilio_number,
				'body' => 'I sent this message in under 10 minutes!'
			)
		);


	}

	public function login () {
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		//$this->load->helper('url');

		if ($username == 'infs' && $password == '3202') {
			$data['username'] = $username;
			$this->load->view('login_success_message', $data);
		} else {
			$this->load->view('login_failure_message');
		}

	}

}



