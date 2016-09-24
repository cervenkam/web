<?php
	require_once 'controller/twig/lib/Twig/Autoloader.php';
	require_once 'controller/functions.php';
	require_once 'model/database_pool.php';
	session_start();
	Twig_Autoloader::register();
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
	if(part_only()){
		$template_page = $page.".tmpl";
	}else{
		$template_page = "structure.tmpl";
	}
	$twig->addGlobal('session',$_SESSION);
	$template = $twig->loadTemplate($template_page);
	echo $template->render(array(
		'page' => $page,
		'privileges' => get_all_privileges()
	));
	DatabasePool::kill();
?>
