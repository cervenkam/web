<?php
	require_once 'twig/lib/Twig/Autoloader.php';
	Twig_Autoloader::register();
	$loader = new Twig_Loader_Filesystem('templates');
	$twig = new Twig_Environment($loader);
	$params = split("/",$_SERVER['REQUEST_URI']);
	if(empty(end($params))){
		$page = 'index';
	}else{
		$page = end($params);
	}
	if(!file_exists('templates/'.$page.'.tmpl')){
		$page = 'error';
	}
	if(isset($_POST['part_only']) && $_POST['part_only'] == 'yes'){
		$template_page = $page.".tmpl";
	}else{
		$template_page = "structure.tmpl";
	}
	$template = $twig->loadTemplate($template_page);
	echo $template->render(array(
		'page' => $page
	));
?>
