<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Offre_v2_model
 */
class OutilOffresModel extends CI_Model
{
	/**
	 * @var
	 */
	protected $connectDB;
	/**
	 * @var string
	 */
	protected string $tableKeyData = 'key_datas';

	/**
	 * Offre_v2_model constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		// $this->connectDB = $this->load->database('global_config', true);
        $this->connectDB = $this->db;
	}

	/**
	 * Récupération de toutes les key datas.
	 */
	public function get_all_data()
	{
		$this->connectDB->select('*');
		$this->connectDB->from($this->tableKeyData);
		$this->connectDB->order_by('id', 'ASC');
		$query = $this->connectDB->get();
		$result = $query->result();
		return $result;
	}

	/**
	 * Insérer une nouvelle key data
	 */
	public function addKeyData($data){
		return $this->connectDB->insert($this->tableKeyData,$data);
	}

	/**
	 * Update d'une offre
	 */
	public function update_keyData($datas, $id){
		$query = $this->connectDB->get_where($this->tableKeyData, ['id'=> $id]);
		$result = $query->row();
		if(isset($result)){
			return $this->connectDB->update($this->tableKeyData, $datas, ['id'=> $id]);
		}
		return $result;
	}

	/**
	 * Suppression d'une key data.
	 */
	public function delete_keyData($id)
	{
		return $this->connectDB->delete($this->tableKeyData,['id' => $id]);
	}

	/**
	 * Vérifier si le nom de la key data est unique.
	 */
	public function name_is_unique($name)
	{
		$query= $this->connectDB->get_where($this->tableKeyData, ['name' => $name]);
		if($query->num_rows() == 0 ){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Compter le nombre de données présents dans la table key_datas
	 */
	public function get_count(){
		return $this->connectDB->count_all($this->tableKeyData);
	}

	/**
	 * Récupérer les keys datas en fonction de la page affichée
	 */
	public function get_key_datas($limit, $start){
		$this->connectDB->limit($limit, $start);
		$query = $this->connectDB->get($this->tableKeyData);

		return $query->result();
	}
}