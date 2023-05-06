<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class KeyDatas extends CI_Controller
{
	/**
	 * Token gitlab pour accès à l'API
	 */
	const GITLAB_TOKEN = 'DQ8KN21syG1XmX5E91Vg';

	public function __construct()
	{
		parent::__construct();
		$this->load->database('global_config');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->model('KeyDatasModel', 'keyDatasModel');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('pagination');
		$css = [base_url('css/key_datas.css?' . time())];
		$this->template->css($css);
		$js = [base_url('js/key_datas.js?' . time())];
		$this->template->js($js);
	}

	/**
	 * Affichage du dashboard des key datas
	 */
	public function dashboard()
	{
		$config = [];
		$config["base_url"] = base_url() . "KeyDatas/dashboard/";
		$config["total_rows"] = $this->keyDatasModel->get_count();
		$config["per_page"] = 15;
		$config["uri_segment"] = 3;
		$config['use_page_numbers'] = TRUE;
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(3)) ? (($this->uri->segment(3)-1) *15) : 0;
		$datas["links"] = $this->pagination->create_links();
		$datas['keyDatas'] = $this->keyDatasModel->get_key_datas($config["per_page"], $page);

		//$datas['keyDatas'] = $this->keyDatasModel->get_all_data();
		$this->template->load('key_datas/dashboard.php', $datas);
	}

	/**
	 * Création d'une nouvelle "key data"
	 */
	public function addKeyData()
	{
		$content = trim(file_get_contents("php://input"));
		$datas = json_decode($content, true);
		$now = new DateTime();
		$date_modification = date_format($now, 'Y-m-d');
		$datas['date_modification'] = $date_modification;

		$result = $this->keyDatasModel->addKeyData($datas);

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
		$result = $this->keyDatasModel->update_keyData($datas, $datas['id']);

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
		$result = $this->keyDatasModel->delete_keyData($id);
		if($result){
			$this->session->set_flashdata('success', 'La key data "'. $name. '" a bien été supprimée.');
		}else {
			$this->session->set_flashdata('error', 'Une erreur est survenue, la key data "'. $name. '" n\'a pas pu être supprimée.');
		}
		redirect('/KeyDatas/dashboard');
	}

	/**
	 * Update de la key data selon son id
	 */
	public function checkNameIsUnique()
	{
		$name = $this->input->get('name');
		$data = $this->keyDatasModel->name_is_unique($name);
		echo (json_encode($data));
	}

}

