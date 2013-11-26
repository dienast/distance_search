<?php
/**
* Database functions for the distance search plugin
* 
* @package OSClass
* @subpackage distance search
* @since 1.0
*/

class searchDistance extends DAO {
	/**
	 * It references to self object: searchDistance.
	 * It is used as a singleton
	 *
	 * @access private
	 * @var searchDistance
	 */
	private static $instance;

	/**
	 * It creates a new searchDistance object class if it has been created
	 * before, it return the previous object
	 *
	 * @access public
	 * @since 1.0
	 * @return searchDistance
	 */
	public static function newInstance() {
		if(!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Construct
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	 * Get name of table cities
	 * @return string
	 */
	public function getTable_Cities() {
		return DB_TABLE_PREFIX.'t_city';
	}
	
	/**
	 * Check if d_coord_lat and d_coord_long exist in cities table
	 * @return boolean
	 */
	public function getColumns_Cities() {
		$sql = "SHOW COLUMNS FROM ".$this->getTable_Cities()." WHERE Field = 'd_coord_lat' OR Field = 'd_coord_long'";
		$result = $this->dao->query($sql);
		$result = $result->result();
		if (empty($result)) {
			return false;
		} else {
			return true; 
		}
	}

	/**
	 * Import sql file
	 * @param type $file 
	 */
	public function import($file) {
		$path = osc_plugin_resource($file) ;
		$sql = file_get_contents($path);
		if(!$this->dao->importSQL($sql)) {
			throw new Exception($this->dao->getErrorLevel().' - '.$this->dao->getErrorDesc());
		}
	}
	
	/**
	 * Update cities table if d_coord_lat and d_coord_long not exist 
	 * @return boolean
	 */
	public function updateTable_Cities() {
		$columns_exists = $this->getColumns_Cities();
		if ($columns_exists) {
			return '';
		} else {
			$this->import('distance_search/update.sql');			
		}		
	}
	
	/**
	 * Get name of table item_location
	 * @return string
	 */
	public function getTable_Item_Location() {
		return DB_TABLE_PREFIX.'t_item_location';
	}

	/**
	 * Get the foreign keys from the item_location table based on distance GET variable
	 * @return array
	 */	
	public function distancefks() {	
		$sCityGET = $_GET['sCity'];		
		if(!empty($sCityGET)){
			$this->dao->select('d_coord_lat,d_coord_long');
			$this->dao->from($this->getTable_Cities());
			$this->dao->where('s_name',$sCityGET);
			$results = $this->dao->get();
			if (!$results) {
				return '';
			}
			$row = $results->row();			
			$lat0 = $row['d_coord_lat'];
			$lng0 = $row['d_coord_long'];					
			$distance = $_GET['distance'];	
			if (!empty($distance) && !empty($lat0) && !empty($lng0)) {
				$queryu = sprintf("SELECT fk_i_item_id, s_city, 
						( 6371 * acos(
						cos(radians($lng0)) * cos(radians(d_coord_lat)) *
						cos(radians(d_coord_long) - radians($lat0)) +
						sin(radians($lng0)) * sin(radians(d_coord_lat))
				) ) AS distance
						FROM `".$this->getTable_Item_Location()."`
						HAVING distance < $distance
						",
						$this->dao->connId->real_escape_string($lat0),
						$this->dao->connId->real_escape_string($lng0),
						$this->dao->connId->real_escape_string($lat0));
				$result = $this->dao->query($queryu);
				$result = $result->result();
				return $result;			
			}
		}		
		else {			
			return false;			
		}	
	}
}		
?>
