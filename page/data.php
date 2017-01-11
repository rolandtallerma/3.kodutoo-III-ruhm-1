<?php 
	// et saada ligi sessioonile
	require("../functions.php");
	
    require("../class/Helper.class.php");
	$Helper = new Helper();
	
	require("../class/Note.class.php");
	$Note = new Note($mysqli);
	
	//ei ole sisseloginud, suunan login lehele
	if(!isset ($_SESSION["userId"])) {
		header("Location: login.php");
		exit();
	}
	
	//kas kasutaja tahab välja logida
	// kas aadressireal on logout olemas
	if (isset($_GET["logout"])) {
		
		session_destroy();
		
		header("Location: login.php");
		exit();
	}
	
	if (	isset($_POST["kaamera"]) && 
			isset($_POST["hind"]) && 
			isset($_POST["seisukord"]) &&
			!empty($_POST["kaamera"]) && 
			!empty($_POST["hind"]) &&
			!empty($_POST["seisukord"])
	) {		
	
		$kaamera = $Helper->cleanInput($_POST["kaamera"]);
		$hind = $Helper->cleanInput($_POST["hind"]);
		$seisukord = $Helper->cleanInput($_POST["seisukord"]);
		$Note->saveNote($kaamera, $hind, $seisukord);
		
	}
	
	$q = "";
	// Otsi sõna aadressirealt
	if(isset($_GET["q"])){
		$q = $Helper->cleanInput($_GET["q"]);	
	}
	
	$sort ="id";
	$order = "ASC";
	if(isset($_GET["sort"]) && isset($_GET["order"])){
		$sort = $_GET["sort"];
		$order = $_GET["order"];	
	}
	$notes = $Note->getAllNotes($q, $sort, $order);
	
	//echo "<pre>";
	//var_dump($notes);
	//echo "</pre>";

?>
<?php require("../header.php"); ?>

<h1>Data</h1>
<p>
	Tere tulemast <a href="user.php"><?=$_SESSION["userEmail"];?></a>!
	<a href="?logout=1">Logi välja</a>
</p>
<h2><i>Märkmed</i></h2>
<form method="POST">
			
	<label>Kaamera</label><br>
	<input name="kaamera" type="text">
	
	<br><br>
	
	<label>Hind</label><br>
	<input name="hind" type="text">
				
	<br><br>
	
	<label>Seisukord</label><br>
	<input name="seisukord" type="text">
	
	<br><br>
	
	<input type="submit">
	
</form>

<h2>arhiiv</h2>
<form>
	<input type="search" name ="q" value="<?=$q;?>">
	<input type="submit" value="otsi">
</form>

	
<h2 style="clear:both;">Tabel</h2>
<?php 

	$html = "<table class='table'>";
	
		$html .= "<tr>";
		
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "id" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=id&order=".$orderId."'>
					id
					</a>
				</th>";
				
				$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "kaamera" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=kaamera&order=".$orderId."'>
					kaamera
					</a>
				</th>";
			
				$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "hind" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=hind&order=".$orderId."'>
					hind
					</a>
				</th>";
					
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "seisukord" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=seisukord&order=".$orderId."'>
					seisukord
					</a>
				</th>";	
		
			$orderId = "ASC";
			if(isset($_GET["order"])&&
				$_GET["order"] == "ASC" &&
				$_GET["sort"] == "seisukord" ){
					
				$orderId = "DESC";
				}
				
		$html .= "<th>
				<a href='?q=".$q."&sort=seisukord&order=".$orderId."'>
					seisukord
					</a>
				</th>";
			
			

		$html .= "</tr>";

	foreach ($notes as $note) {
		$html .= "<tr>";
			$html .= "<td>".$note->id."</td>";
			$html .= "<td>".$note->kaamera."</td>";
			$html .= "<td>".$note->hind."</td>";
			$html .= "<td>".$note->seisukord."</td>";
			$html .= "<td><a href='edit.php?id=".$note->id."'>edit.php</a></td>";
		$html .= "</tr>";
	}
	
	$html .= "</table>";
	
	echo $html;

?>

<?php require("../footer.php"); ?>





































