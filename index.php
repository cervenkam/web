<?php
	require_once 'twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader);
	$template = $twig->loadTemplate('index.tmpl');
	echo $template->render(array(
		'name' => 'Fabien',
		'title' => 'Title'
	));
?>
