<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OutilOffresController extends CI_Controller {

	public $css;
	private $js;
	public function __construct()
	{
		parent::__construct();
		$this->css = [base_url('public/assets/css/outilOffres/style.css?' . time())];
		$this->js = [base_url('public/assets/js/outilOffres/app.js?' . time())];
        $this->load->model('outilOffres/OutilOffresModel', 'OutilOffresModel');
	}

    /**
	 * Affichage du dashboard des key datas
	 */
	public function dashboard()
	{
        $config = [];
		$config["base_url"] = base_url() . "/outilOffres";
		$config["total_rows"] = $this->OutilOffresModel->get_count();
		$config["per_page"] = 15;
		$config["uri_segment"] = 3;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? (($this->uri->segment(3)-1) *15) : 0;
		$datas["links"] = $this->pagination->create_links();
		$datas['keyDatas'] = $this->OutilOffresModel->get_key_datas($config["per_page"], $page);

		//$datas['keyDatas'] = $this->keyDatasModel->get_all_data();
		$this->load->view('layout/header', ['css' =>$this->css]);
		$this->load->view('outilOffres/index', $datas);
		$this->load->view('layout/footer',['js' =>$this->js]);
	}

	/**
	 * Création d'une nouvelle "key data"
	 */
	public function add()
	{
		$content = trim(file_get_contents("php://input"));
		$datas = json_decode($content, true);
		$now = new DateTime();
		$date_modification = date_format($now, 'Y-m-d');
		$datas['date_modification'] = $date_modification;

		$result = $this->OutilOffresModel->addKeyData($datas);

		if(isset($result)) return $result;
		else {
			throw new \Exception('Une erreur est survenue, la key data n\'a pas pu être créée
			 .');
		}
	}

	/**
	 * Update d'une "key data"
	 */
	public function update(){
		$content = trim(file_get_contents("php://input"));
		$datas = json_decode($content, true);
		$now = new DateTime();
		$date_modification = date_format($now, 'Y-m-d');
		$datas['date_modification'] = $date_modification;
		$result = $this->OutilOffresModel->update_keyData($datas, $datas['id']);

		if(isset($result)) return $result;
		else {
			throw new \Exception('Une erreur est survenue, la key data n\'a pas pu être mise à jour.');
		}
	}

	/**
	 * Suppression d'une "key data"
	 */
	public function delete()
	{
		$id = $this->input->post('id');
		$name = $this->input->post('name');
		$result = $this->OutilOffresModel->delete_keyData($id);
		if($result){
			$this->session->set_flashdata('success', 'La key data "'. $name. '" a bien été supprimée.');
		}else {
			$this->session->set_flashdata('error', 'Une erreur est survenue, la key data "'. $name. '" n\'a pas pu être supprimée.');
		}
		redirect('/outilOffres');
	}

	/**
	 * Update de la key data selon son id
	 */
	public function checkNameIsUnique()
	{
		$name = $this->input->get('name');
		$data = $this->OutilOffresModel->name_is_unique($name);
		echo (json_encode($data));
	}
}