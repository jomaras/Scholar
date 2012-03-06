<?php
require_once("php/domParser/simple_html_dom.php");

function getGoogleData()
{
	$html = file_get_html('http://scholar.google.com/scholar?q=Slicing+Web+application+code');
	
	// get news block
	foreach($html->find('.gs_rt') as $paperTitleElement) 
	{
		echo($paperTitleElement->plaintext . "<br/>");
	}
}

getGoogleData();