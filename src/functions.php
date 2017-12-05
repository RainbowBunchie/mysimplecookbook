<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<?php
ini_set('display_errors', true);

include "config.php";

try {
	$dbh = new PDO($DSN, $DB_USER, $DB_PASS);
	$dbh -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbh -> setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

} catch (Exception $e) {
	die("Problem connecting to database $DB_NAME as $DB_USER: " . $e -> getMessage());
}
?>