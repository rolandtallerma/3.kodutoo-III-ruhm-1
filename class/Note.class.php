<?php 
class Note {
	
    private $connection;
	
	function __construct($mysqli){
		$this->connection = $mysqli;
	}
	
	/* KLASSI FUNKTSIOONID */
    
    function saveNote($kaamera, $hind, $seisukord) {
		
		$stmt = $this->connection->prepare("INSERT INTO colorNotes2 (kaamera, hind, seisukord) VALUES (?, ?, ?)");
		echo $this->connection->error;
		
		$stmt->bind_param("sss", $kaamera, $hind, $seisukord );

		if ( $stmt->execute() ) {
			echo "salvestamine õnnestus";	
		} else {	
			echo "ERROR ".$stmt->error;
		}
		
	}
	
	
	function getAllNotes($q, $sort, $order) {
		// Lubatud tulbad
		$allowedSort = ["id", "kaamera", "hind", "seisukord"];
		if(!in_array($sort, $allowedSort)){
			// Ei olnud lubatud tulpade sees
			$sort = "id"; // Las sorteerib id järgi
		}
		
		$orderBy = "ASC";
		if($order == "DESC"){
				$orderBy = "DESC";	
		}
		
		echo "sorteerin ".$sort." ".$orderBy." ";
		//otsime
		if($q !=""){
			echo "Otsin: ".$q;
			$stmt = $this->connection->prepare("
				SELECT id, kaamera, hind, seisukord
				FROM colorNotes2
				WHERE deleted IS NULL
				AND (kaamera LIKE ? OR hind LIKE ? OR seisukord LIKE ?)
				ORDER BY $sort $orderBy
		");
		$searchWord = "%".$q."%";
		$stmt->bind_param("sss", $searchWord, $searchWord, $searchWord);
		}else{
			//ei otsi
			$stmt = $this->connection->prepare("
				SELECT id, kaamera, hind, seisukord
				FROM colorNotes2
				WHERE deleted IS NULL
				ORDER BY $sort $orderBy
		");
		}
		$stmt->bind_result($id, $kaamera, $hind, $seisukord);
		$stmt->execute();
		
		$result = array();
		
		while ($stmt->fetch()) {
			//echo $note."<br>";
			
			$object = new StdClass();
			$object->id = $id;
			$object->kaamera = $kaamera;
			$object->hind = $hind;
			$object->seisukord = $seisukord;
			
			
			array_push($result, $object);
			
		}
		
		return $result;
		
	}
	
	function getSingleNoteData($edit_id){
    		
		$stmt = $this->connection->prepare("SELECT kaamera, hind, seisukord FROM colorNotes2 WHERE id=? AND deleted IS NULL");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($kaamera, $hind, $seisukord);
		$stmt->execute();
		
		//tekitan objekti
		$n = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$n->kaamera = $kaamera;
			$n->hind = $hind;
			$n->seisukord = $seisukord;
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();		
		return $n;
		
	}


	function updateNote($id, $kaamera, $hind, $seisukord){
				
		$stmt = $this->connection->prepare("UPDATE colorNotes2 SET kaamera=?, seisukord=?, hind=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("sssi",$kaamera, $hind, $seisukord, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
	}
	
	function deleteNote($id){
		
		$stmt = $this->connection->prepare("
			UPDATE colorNotes2 
			SET deleted=NOW() 
			WHERE id=? AND deleted IS NULL
		");
		$stmt->bind_param("i", $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		
	}
} 
?>