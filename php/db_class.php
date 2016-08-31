<?php 

class DB{
	
	public $table;
	
	public function __construct(){
		
		$this->table = ACTIVE_TABLE;
		
		try{
			$this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		catch(PDOException $e){
			$msg = "FEHLER: " . $e->getMessage();
			//echo $msg;
			die();
		}
		
	}
	
/* ======================================================================== */
	
	public function getRow($id){
		$query = "SELECT * FROM {$this->table} WHERE id = ?";
		
		try{
			$stmt = $this->db->prepare($query);
			$stmt->execute([$id]);
			$result = $stmt->fetch();
			return $result;
			
		}catch(PDOException $e){
			$msg = "FEHLER: " . $e->getMessage();
			//echo $msg;
		}
	}
	
/* ======================================================================== */
	
	public function getAllRows(){
		$query = "SELECT * FROM {$this->table}";
		try{
			$stmt = $this->db->prepare($query);
			$stmt->execute();
			$rows = $stmt->fetchAll();
			return $rows;
		}catch(PDOException $e){
			//echo $e->getMessage();
		}
	}
	
/* ======================================================================== */
	
	/**
	 * updates one row of data from the table
	 * @param mixed $nr
	 * @param array $new_values
	 */
	public function updateRow($new_values){
		
		$params = array(
				$new_values['jahre'],
				$new_values['projekt'],
				$new_values['beschreibung'],
				$new_values['summe'],
				$new_values['id']
		);
		
			
		$query = "UPDATE {$this->table} SET jahre = ?, projekt = ?, beschreibung = ?, summe = ? WHERE id = ?";
		
		try{
			$stmt = $this->db->prepare($query);
			$stmt->execute($params);
			return true;
		}catch(PDOException $e){
			$msg = "FEHLER: " . $e->getMessage();
			//echo $msg;
			return false;
		}
			
	}

/* ======================================================================== */
	
	/**
	 * inserts a new entry
	 * @param array $new_values
	 * @return boolean
	 */
	public function insertRow($new_values){
	
		$params = array(
				$new_values['jahre'],
				$new_values['projekt'],
				$new_values['beschreibung'],
				$new_values['summe']
		);
	
			
		$query = "INSERT INTO {$this->table} (jahre, projekt, beschreibung, summe) VALUES (?, ?, ?, ?)";
	
		try{
			$stmt = $this->db->prepare($query);
			$stmt->execute($params);
			return true;
		}catch(PDOException $e){
			$msg = "FEHLER: " . $e->getMessage();
			return false;
		}
			
	}	
	
/* ======================================================================== */
	
	/**
	 * deletes a row
	 * @param mixed $id
	 * @return boolean
	 */
	public function deleteRow($id){
		
		$id = intval($id);
		
		$query = "DELETE FROM {$this->table} WHERE id = ?";
		
		try{
			$stmt = $this->db->prepare($query);
			$stmt->execute([$id]);
			return true;
		}catch(PDOException $e){
			$msg = "FEHLER: " . $e->getMessage();
			return false;
		}
		
	}
	
/* ======================================================================== */
	
	/**
	 * validates the input before updating database or inserting new entry
	 * @param mixed $nr
	 * @param array $new_values
	 * @return array
	 */
	public function valid_input($new_values){
		
		if(isset($new_values['id'])){
			$valid_id = intval($new_values['id']); 
		}else{
			$valid_id = 0;
		}
		
		$valid_date = filter_var($new_values['jahre'], FILTER_VALIDATE_REGEXP, array(
				"options" => array("regexp" => "/^[0-9\s-]+$/"
		)));
		
		$valid_project = filter_var($new_values['projekt'], FILTER_VALIDATE_REGEXP, array(
				"options" => array("regexp" => "/^[a-zA-Z0-9öüäß\s\.\,-]+$/"
		)));
		
		$clean_desc = htmlspecialchars($new_values['beschreibung']);
		
		$valid_sum = intval($new_values['summe']);
		
		$valid_array = array(
				'jahre' => $valid_date,
				'projekt' => $valid_project,
				'beschreibung' => $clean_desc,
				'summe' => $valid_sum,
				'id' => $valid_id
		);
		
		return $valid_array;
		
	}
	
/* ======================================================================== */
	
	/**
	 * sanitizes output from the database
	 * @param array $output
	 * @return array
	 */
	public function sanitize_output($output){
		
			
		$clean_id = intval($output['id']);
	
		$clean_date = htmlspecialchars($output['jahre'], ENT_QUOTES);
	
// 		$clean_project = htmlspecialchars($output['projekt'], ENT_DISALLOWED);
	
// 		$clean_desc = htmlspecialchars($output['beschreibung'], ENT_DISALLOWED);
	
		$clean_project = strip_tags($output['projekt'], ENT_QUOTES);
		
		$clean_desc = strip_tags($output['beschreibung'], ENT_QUOTES);
		
		$clean_sum = intval($output['summe']);
			
		$sanitized_array = array(
				'id' => $clean_id,
				'jahre' => $clean_date,
				'projekt' => $clean_project,
				'beschreibung' => $clean_desc,
				'summe' => $clean_sum
		);
		
		return $sanitized_array;
	
	}
	
}//end of class



