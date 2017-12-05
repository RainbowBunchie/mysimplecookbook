<!--
 -------Impressum (English)-------
 This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

 -------Impressum (German)-------
 Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
 -->
 
 <?php

$pagetitle = "Log out";

//Session zerstören

if (isset($_COOKIE[session_name()])) {
	setcookie(session_name(), '', time() - 42000, '/');
}
session_destroy();

include "header.php";
?>

<div class="container">
	<h1> logout </h1>
	<?php

	if (!isset($_SESSION['loggedin'])):

	?>

	<h2 class = "center" > You must be logged in to log out! </h2>
	<?php else: ?>
	<p class="center">
		You have been sucessfully logged out!
	</p>
	<?php endif; ?>
</div>

<?php
include "footer.php";
?>