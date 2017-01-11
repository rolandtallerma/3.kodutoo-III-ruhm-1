<?php
	//edit.php
	require("../functions.php");
	
    require("../class/Helper.class.php");
	$Helper = new Helper();

	require("../class/Note.class.php");
	$Note = new Note($mysqli);
	
	/// kas aadressireal on delete
	if(isset($_GET["delete"])){
		// saadan kaasa aadressirealt id
		$Note->deleteNote($_GET["id"]);
		header("Location: data.php");
		exit();
		
	}
	
	//kas kasutaja uuendab andmeid
	if(isset($_POST["update"])){
		
		$Note->updateNote($Helper->cleanInput($_POST["id"]), $Helper->cleanInput($_POST["kaamera"]), $Helper->cleanInput($_POST["hind"]), $Helper->cleanInput($_POST["seisukord"]));
		
		header("Location: edit.php?id=".$_POST["id"]."&success=true");
        exit();	
		
	}
	
	//saadan kaasa id
	$c = $Note->getSingleNoteData($_GET["id"]);
	//var_dump($c);

	
?>
<?php require("../header.php"); ?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
  	<label for="kaamera" >Kaamera</label><br>
	<textarea  id="kaamera" name="kaamera"><?php echo $c->kaamera;?></textarea><br>
	<label for="hind" >Hind</label><br>
	<textarea  id="hind" name="hind"><?php echo $c->hind;?></textarea><br>
	<label for="seisukord" >Seisukord</label><br>
	<textarea  id="seisukord" name="seisukord"><?php echo $c->seisukord;?></textarea><br>
  	
	<input type="submit" name="update" value="Salvesta">
  </form>
  
<br>
<br>
<a href="?id=<?=$_GET["id"];?>&delete=true">kustuta</a>
  
<?php require("../footer.php"); ?> 
  
  
  
  
  
  