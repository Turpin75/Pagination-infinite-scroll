<?php

	try
	{
		$bdd = new PDO('mysql:host=127.0.0.1;dbname=tests_php;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch(Exception $e)
	{
		die('Erreur : ' . $e->getMessage());
	}

	$jeuxParPage = 5;
	$jeuxTotalReq = $bdd->query('SELECT ID FROM jeux_video');
	$jeuxTotal = $jeuxTotalReq->rowCount();

	$pagesTotales = ceil($jeuxTotal / $jeuxParPage);

	if(isset($_GET['page']) AND !empty($_GET['page']) AND $_GET['page'] > 0 AND $_GET['page'] <= $pagesTotales)
	{
		$_GET['page'] = intval($_GET['page']);
		$pageCourante = $_GET['page'];
	}
	else
	{
		$pageCourante = 1;
	}

	// Déterminera le premier élément de LIMIT dans la base de données
	$debut = ($pageCourante - 1) * $jeuxParPage;

?>

<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title> Pagination </title>
		<!-- Récupération de jquery sur jquery hosted librairies -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>

	<body>

		<div id="tous_les_jeux">
		<?php
			$jeux = $bdd->query('SELECT * FROM jeux_video ORDER BY id DESC LIMIT ' . $debut . ', ' . $jeuxParPage);
			while($jeu = $jeux->fetch())
			{
				?>
				<div class="jeux" style="background-color: lightgray; margin-bottom: 10px;">
				<?php
				echo '<p> <strong> N°: ' . $jeu['ID'] . ' ' . $jeu['nom'] . ' : </strong></br>' . $jeu['commentaires'] . '</br></br>';
				?>
				</div>
				<?php
			}
			$jeux->closeCursor();
		?>
		</div>
		<div id="pagination">
		<?php
			for($i = 1; $i <= $pagesTotales; $i++)
			{
				if($i == $pageCourante)
				{
					echo $i . ' ';
				}
				elseif ($i == $pageCourante + 1) 
				{
					echo '<a href="pagination.php?page='.$i.'" class="suivant" >' .$i. '</a>  ';
				}
				else
				{
					echo '<a href="pagination.php?page='.$i.'" >' .$i. '</a>  ';
				}
			}
		?>
		</div>

	</body>

	
	<script type="text/javascript" src="js/jquery-ias.min"></script>
	<script type="text/javascript">
		// Permet d'activer l'effet infinite scroll comme pagination
		var ias = jQuery.ias({
		  container:  '#tous_les_jeux',
		  item:       '.jeux',
		  pagination: '#pagination',
		  next:       '.suivant'
		});

		// Ajout d'un loader pour montrer que du contenu est entrain de charger
		// On peut enlever la totalité du contenu de la fonction, à savoir la ligne "src: 'images/ajax-loader.gif'," pour avoir une image par défaut
		// Ou trouver une image de loader comme sur ajaxload.info, télécharger l'image, et la mettre en source comme pour l'exemple
		ias.extension(new IASSpinnerExtension({
    		src: 'images/ajax-loader.gif', // optionally
		}));
		
		// Affiche le message text à la fin, quand il n'y aura plus d'articles à charger par exemple.
		ias.extension(new IASNoneLeftExtension({
		    text: 'Fin de la liste de jeux.', // optionally
		}));

		// Permet d'afficher un lien avec comme texte le contenu de la balise html, qui demande par exemple si on veut afficher plus de jeux.
		// Contenu html qui peut bien-sûr être personnalisé
		// offset permettra de n'afficher ce lien qu'aprés la Nieme page, là en l'occurrence qu'aprés la deuxième page 
		ias.extension(new IASTriggerExtension({
		    html: '<div class="ias-trigger ias-trigger-next" style="text-align: center; cursor: pointer; text-decoration: underline; border: 1px solid black; background-color: gray; color : white; padding-top: 5px; padding-bottom: 5px;"><a> Afficher plus de jeux </a></div>', // optionally
		    offset: 2,
		}));
	</script>

</html>