<?php
$fichier = file("param/news.txt");
$doc = "";
foreach($fichier as $key=>$value) {
	if (substr($value,1,1)=="*" or substr($value,0,1)=="*"){
		$doc .= "&nbsp;&nbsp;&nbsp;";
	}
	if ($APPLI_utf8==true) utf8_encode($value);
	$doc .= htmlentities($value)."<br>";
}

$vue->set($doc, "texteNews");
$vue->set("documentation/quoideneuf.tpl", "corps");
?>