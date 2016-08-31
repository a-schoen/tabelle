<?php
require_once 'config.php';
require_once 'db_class.php';


if(isset($_POST['data'])){
	
	$data = json_decode($_POST['data'], true);
	
	$action = $data['action'];
	
	if(isset($data['content'])){
		$new_data = $data['content'];
	}

	$db = new DB();
	
	// fetch data from the db
	if($action == "get_data"){
		
		$rows = $db->getAllRows();
		
		$projekte = array();
		
		foreach($rows as $row){
			$rows = $db->sanitize_output($row);
			$projekte[] = $row;
		}
		
		$data = array(
				'projekte' => $projekte
		);
		
		$json = json_encode($data, JSON_UNESCAPED_UNICODE, 3);
		
		echo $json;
	}
	// add new entry
	else if($action == "add_entry"){
		
		$new_data = json_decode($data['content'], true);
		
 		$valid_input = $db->valid_input($new_data);
			
   		print_r($valid_input);
		
		$add = $db->insertRow($valid_input);
		
		if($add){
			echo 'Eintrag hinzugefügt';
		}else{
			echo 'Fehler beim hinzufügen';
		}
	}
	// delete an entry
	else if($action == "delete_entry"){
		
		$id = $new_data['id'];
				
		$delete = $db->deleteRow($id);
		
		if($delete){
			echo 'Löschen erfolgreich';
		}else{
			echo 'Fehler beim Löschen von Eintrag Nr ' . $id;
		}
		
	}
	//store entry
	else if($action == "store_data"){
		
		$new_data = json_decode($data['content'], true);
		
		$valid_input = $db->valid_input($new_data);
				
		$save = $db->updateRow($valid_input);
	
		if($save){
		 	echo 'Eintrag Nr ' . $item['id'] . 'wurde aktualisiert';
		 }else{
	 		echo 'Eintrag Nr ' . $item['id'] . 'konnte nicht aktualisiert werden';
	 	}
		
	}	
	
}