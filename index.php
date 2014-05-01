<?
ini_set('display_errors','1');
if (file_exists('variables.xml')){
	header('Location: app/');
}else{
	header('Location: install/');
}