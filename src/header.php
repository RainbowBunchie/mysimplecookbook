<!-- 
-------Impressum (English)-------
This website was created as multi media project 1 at the University of Applied Science Salzburg (MultiMedia Technology) by Denise Buder.

-------Impressum (German)-------
Diese Website entstand als Multimedia Projekt 1 im Studiengang MutliMedia Techology an der Fachhochschule Salzburg und wurde von Denise Buder umgesetzt.
-->

<!DOCTYPE html>
<html>
	<head> 
	    <!-- Code damit das teilen richtig funktioniert (damit der Text passt und nicht nur die url da steht)  --> 
        <meta property="og:type"               content="website" />
        <meta property="og:title"              content="Check out awesome recipes!"/>
        <meta property="og:description"        content="Click the link for the recipe!" />
        <!-- Ende -->
		
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
		
        <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Gloria+Hallelujah|Indie+Flower" rel="stylesheet">
		<link href="style.css" rel="stylesheet">
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="dist/imgareaselect.css">
		
		<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.12.4.min.js"></script>
		
		<script>
			$(document).ready(function() { 
				//für die mobile Version, damit onclick auf das Burger-Symbol das Menü ausgeklappt wird
				$('#burger').on('click', function() {
					$('#navigationrechts').toggleClass('toggle');					
				});
				
				//setzt den Footer an den unteren Rand des Bildschirms, falls der Inhalt nicht den gesamten Bildschirm ausfüllt
				var docHeight = $(window).height();
				var footerHeight = $('#footernav').height();
				var footerTop = $('#footernav').position().top + footerHeight;

				if (footerTop < docHeight) {
					$('#footernav').css('margin-top', (docHeight - footerTop));
				}
			});
		</script>
		
		<?php
		session_start();

		include "functions.php";
		
		//Überprüfen ob jemand eingeloggt ist -> letzter Menüpunkt umsetzen, je nachdem ob jemand eingeloggt ist

		if (isset($_SESSION['ID']) and $_SESSION['ID'] == true) {
			$login = "profile";
			$href = "?id=" . $_SESSION['ID'];
		} else {
			$login = "login";
			$href = "";
		}
		error_reporting(0); //zum debuggen auskommentieren!
        ?>
      <title> <?php echo $pagetitle ?></title>
      <link rel="stylesheet" href="style.css">  
     
    </head>
    <body>
    <!-- Code von Developertools https://developers.facebook.com/docs/plugins/share-button -->
  <div id="fb-root"></div>
		<script>
			(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.9";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <!-- Ende Code von Developertools --> 
      <div> 
        <div class="navigation">
            <div class="container"> 
            	<img id="burger" src="img/burger.png" alt="mobile-navigation" title="mobile navigation">
                <ul id="navigationlinks">
                    <li> <a href="index.php"> my<span id="logocolor">simple</span>cookbook </a> </li>
                </ul>
                <ul id="navigationrechts">
                    <li> <a href="share.php"> share </a> </li>
                    <li> <a href="categories.php"> categories </a> </li>
                    <li> <a href="search.php"> search </a>  </li>
					<li> <a href='<?php echo $login ?>.php<?php echo $href ?>'> <?php echo $login ?> </a> </li>
                </ul>
            </div>
        </div>