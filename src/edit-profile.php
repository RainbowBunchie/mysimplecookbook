<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<?php	

   	$pagetitle = "Edit your profile";
		
	$update = true;

    include "header.php";
	
	$id = $_GET['id'];	
		
	$userhelp = $dbh->prepare("SELECT * FROM users WHERE id='$id';");
	$userhelp->execute();
	$user= $userhelp->fetch();
	
	$sth = $dbh->prepare("UPDATE users set firstname = ?, lastname =?, email=?, username=?, isfemale=? where id=$id;");
	
	$deleteshare = $dbh->prepare("DELETE FROM recipes where shared_by=$id;");
	$deletefav = $dbh->prepare("DELETE FROM favorites where id_user=$id;");
	$deletecomments =  $dbh->prepare("DELETE FROM comments where id_user=$id;");
	$deleteratings = $dbh->prepare("DELETE FROM ratings where id_user=$id;");
	$deleteuser = $dbh->prepare("DELETE FROM users where id=$id;");
	
	$fehler = false;
	
	//Checken ob User berechtigt ist das Profil zu editieren: 
	
 	if (!isset($_SESSION['loggedin'])){ 
		echo  "<h2 class='center loggedin'> You must be logged in to edit your profile </h2>"; 
		$fehler = true;
	}
    else if ($_SESSION['ID'] != $user->id){
		echo "<h2 class='center loggedin'> You are not allowed to edit other people's profile </h2>";
		$fehler = true;
	}
	
	if(!$fehler): 
	
	//Wenn das Profil gelöscht werden soll
	
	if (isset ($_POST['deleteprofile'])){
		$commentcounthelp = $dbh->prepare( "SELECT count(*) AS c FROM comments WHERE id_user = $id;");
		$commentcounthelp->execute();
		$commentcount = $commentcounthelp->fetch();
		$favecounthelp = $dbh->prepare( "select count(*) AS c from recipes, favorites where recipes.id = favorites.id_recipe and id_user = $id");
		$favecounthelp->execute();
		$favecount = $favecounthelp->fetch();
		$sharecounthelp = $dbh->prepare( "SELECT count(*) AS c FROM recipes WHERE shared_by = $id;");
		$sharecounthelp->execute();
		$sharecount = $sharecounthelp->fetch();
		$ratingcounthelp = $dbh->prepare( "SELECT count(*) AS c FROM ratings WHERE id_user = $id;");
		$ratingcounthelp->execute();
		$ratingcount = $ratingcounthelp->fetch();

		if (file_exists("imgprofile/".$id.".jpg")){
			unlink( "imgprofile/" . $id . ".jpg");
		}
		echo "Favecount ".var_dump($commentcount);
		if ($favecount->c > 0){ 
			$deletefav->execute();
		}
		if ($commentcount->c > 0 ){ 
			$deletecomments->execute();
		}
		if ($sharecount->c > 0 ){
			$idrecipeshelp = $dbh->prepare( "SELECT id FROM recipes WHERE shared_by = '$id';");
			$idrecipeshelp->execute();
			$idrecipes = $idrecipeshelp->fetchAll();
			echo var_dump($idrecipes);
			foreach($idrecipes as $recipe) {
				unlink( "imgrecipe/" . $recipe->id . ".jpg"); 
	
				$commentcountrecipehelp = $dbh->prepare( "SELECT count(*) AS c FROM comments WHERE id_recipe = '$recipe->id';");
				$commentcountrecipehelp->execute();
				$commentcountrecipe = $commentcountrecipehelp->fetch();
				$favecountrecipehelp = $dbh->prepare( "SELECT count(*) AS c FROM favorites WHERE id_recipe = '$recipe->id';");
				$favecountrecipehelp->execute();
				$favecountrecipe = $favecountrecipehelp->fetch();
				$ratingcountrecipehelp = $dbh->prepare("SELECT count(*) c FROM ratings WHERE id_recipe = $recipe->id;");
				$ratingcountrecipehelp->execute();
				$ratingcountrecipe = $ratingcountrecipehelp->fetch();			
				
				$deletecommentsrecipe =  $dbh->prepare("DELETE FROM comments where id_recipe=$recipe->id;");
				$deletefavrecipe = $dbh->prepare("DELETE FROM favorites where id_recipe=$recipe->id;");
				$deleteratingsrecipe = $dbh->prepare("DELETE FROM ratings where id_recipe=$recipe->id;");
				
				if ($favecountrecipe->c > 0){ $deletefavrecipe->execute();}
				if ($commentcountrecipe->c > 0 ){ $deletecommentsrecipe->execute();}
				if ($ratingcountrecipe->c > 0) {$deleteratingsrecipe->execute();}
			}
			$deleteshare->execute();
		}		
		if ($ratingcount->c > 0 ){ $deleteratings->execute();}

		$deleteuser->execute();
		
		if (isset($_COOKIE[session_name()])){
			setcookie(session_name(),'', time()-42000, '/');
			session_destroy();
		}
		header("Location: index.php");             
	}
	
	//Bei einem Update werden alle Eingaben überprüft 
	if (isset ($_POST['submit'])){
		
		$fname = strip_tags($_POST['firstname']);
		if (($fname == " ")||(!$fname)) {
			$error[0] = "First name is empty!";
		}
		$sname = strip_tags($_POST['lastname']);
		if (($sname == " ")||(!$sname)) {
			$error[1] = "Last name is empty!";
		}
		$mail = htmlspecialchars(strip_tags($_POST['email']));
		if ($mail != $user->email){
			if (($mail == " ")||(!$mail)||(strpos($mail, "@") == false)) {
				$error[2] = "Wrong e-mail input!";
			}			
			$emailcheckhelp = $dbh->prepare("SELECT email FROM users WHERE email='$mail';");
			$emailcheckhelp->execute();
			$person = $emailcheckhelp->fetch();
			if($person != ""){
				$error[3] = "This e-mail adress is already taken!";
			}
		}	
		$username = strip_tags($_POST['username']);
		if ($username != $user->username){
			if (($username == " ")||(!$username)) {
				$error[4] = "Username is empty!";
			}
			
			$usernamecheck = $dbh->prepare("SELECT username FROM users WHERE username='$username';");
			$usernamecheck->execute();
			$person = $usernamecheck->fetch();
			if($person != ""){
				$error[5] = "This username is already taken!";
			}
		}
				
		$isfemale = $_POST['isfemale'];
		
		if(isset ($error)) {
			echo "<h2 class = 'error center'>Something went wrong:</h2> <ul class='error center'>";
			foreach ($error as $error_message) {
				echo "<li>$error_message</li><br>";
			}
			echo "</ul>";
		}
		else{
			 $sth->execute(array($fname, $sname, $mail, $username, $isfemale));
			if (file_exists("imgprofile/tmp.jpg")) {
					if (file_exists("imgprofile/".$id.".jpg")) {
						unlink($filename); //falls schon ein Bild vorhanden und ein neues gespeichert wird, dann dieses zuerst löschen
					}				
					rename ("imgprofile/tmp.jpg", "imgprofile/".$id.".jpg");
				} 
			 header("Location: profile.php?id=$user->id");           
		}
    }
?>

<div class="container">
    <h1> Edit your profile </h1>
    <?php
    include "form.php";
    ?>
    <h1> Delete your profile </h1>
    <p class="center"> This process will delete your profile forever. You can not restore it later on. </p>
    <div class="formular">
        <form method="POST">
            <input class="buttonred" type = "submit" name="deleteprofile" value="Delete your profile" />
        </form>
    </div>
    <?php
echo "</div>";
endif;
include "footer.php";
?>
