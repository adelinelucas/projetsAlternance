<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FormHelperController extends CI_Controller {

	public $css;
	private $js;
	public function __construct()
	{
		parent::__construct();
		$this->css = [base_url('public/assets/css/helperForm/style.css?' . time())];
		// $this->template->css($css);
		$this->js = [base_url('public/assets/css/helperForm/app.js?' . time())];
		// $this->template->js($js);
	}
	public function index()
	{
		// var_dump( $this->css);
		$this->load->view('layout/header', ['css' =>$this->css]);
		$this->load->view('formHelper/index');
		$this->load->view('layout/footer');
	}
}