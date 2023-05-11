<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous"> -->
	<link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css'); ?>">
    <?php 
    if(isset($css) && !is_null($css)){
        foreach($css as $link) : 
           echo "<link rel='stylesheet' href='". $link."')'>";
        endforeach;
    }
    ?>
    <title>Projets d'alternance</title>
</head>
<body>
	<nav class="navbar navbar-expand-lg p-4" style="background-color: #E8B722;">
		<div class="container-fluid">
			<a class="navbar-brand" href="<?= base_url('/'); ?>">
				<img src="<?= base_url('public/assets/images/screen.png'); ?>" alt="image d'un laptop" width="100px"/>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
			<ul class="navbar-nav">
				<li class="nav-item">
				<a class="nav-link active" aria-current="page" href="">Page d'accueil</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="<?= base_url('/formHelper'); ?>">Helper Form</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="#">Outil générateur de landings</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="#">Outil générateur d'offres</a>
				</li>
				<li class="nav-item">
				<a class="nav-link" href="#">Outil keys datas</a>
				</li>
			</ul>
			</div>
		</div>
	</nav>