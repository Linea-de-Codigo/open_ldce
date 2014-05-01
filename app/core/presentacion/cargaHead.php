<?php
if ($texto !=1 ){
$javascript = file_get_contents("../../modulos/".$_GET['js']); 
$css = file_get_contents("../../modulos/".$_GET['css']); 
}else{
    $javascript=$_GET['js'];
    $css = $_GET['css'];
}
$xml="<?xml version='1.0' encoding='ISO-8859-1'?>";
$xml.="<code>";
$xml.="<javascript><![CDATA[ $javascript ]]></javascript>";
$xml.="<css><![CDATA[ $css ]]></css>";
$xml.="</code>";
header("Content-type: text/xml");
echo $xml;
?>
