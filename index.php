<?php
	require_once 'controller/twig/lib/Twig/Autoloader.php';
	require_once 'model/database_pool.php';
	Twig_Autoloader::register();
	DatabasePool::init('mysql:host=localhost;dbname=web','web','password');
	$loader = new Twig_Loader_Filesystem('view/templates');
	$twig = new Twig_Environment($loader);
	$params = split("/",$_SERVER['REQUEST_URI']);
	if(empty(end($params))){
		$page = 'index';
	}else{
		$page = end($params);
	}
	if(!file_exists('view/templates/'.$page.'.tmpl')){
		$page = 'error';
	}
	if(isset($_POST['part_only']) && $_POST['part_only'] == 'yes'){
		$template_page = $page.".tmpl";
	}else{
		$template_page = "structure.tmpl";
	}
	$template = $twig->loadTemplate($template_page);
	echo $template->render(array(
		'page' => $page,
		'pool' => DatabasePool::instance()
	));
?>
