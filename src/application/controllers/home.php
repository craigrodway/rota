<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function index()
	{
		$data['title'] = 'Welcome to Railways on the Air';
		$data['body'] = $this->load->view('home/index', null, true);
		$this->page($data);
	}
	
	
	
	public function about()
	{
		$data['title'] = 'About the event';
		$data['body'] = '';	//$this->load->view('home/about');
		$this->page($data);
	}
	
	
	
	
	public function contact()
	{
		$data['title'] = 'Contact us';
		$data['body'] = '';
		$this->page($data);
	}
	
	
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */