<?php
require_once("php/domParser/simple_html_dom.php");
require_once("php/helpers/ResponseHelper.php");
require_once('php/simpletest/browser.php');

function findFirstNonEmptyLine($string)
{
	foreach(explode("\n", $string) as $line)
	{
		if(trim($line) != "") { return $line; }
	}
	
	return "";
}

function getGoogleData()
{
	ResponseHelper::DefineBrowserHeaders();

	$browser = new SimpleBrowser();
	$browser->get('http://ieeexplore.ieee.org/search/searchresult.jsp?action=search&sortType=&rowsPerPage=10&searchField=Search%20All%20Text&matchBoolean=true&queryText=((.QT.test%20case.QT.)%20AND%20.QT.unit.QT.)');
	
	$html = file_get_html_from_string($browser->getContent());
	
	foreach($html->find('.Results li') as $searchResultElement) 
	{
		$possibleTitleElements = $searchResultElement->find("h3 a");
		$possibleAuthorsElements = $searchResultElement->find("p");
		$possibleDoiElements = $searchResultElement->find("a[href^=http://dx.doi.org/]");
		$possibleAbstractElements = $searchResultElement->find(".abstract");
		$possiblePdfLinksElements = $searchResultElement->find(".links a");
		
		
		$title = sizeof($possibleTitleElements) > 0 ? $possibleTitleElements[0]->plaintext : "Unknown title";
		$authors = sizeof($possibleAuthorsElements) > 0 ? findFirstNonEmptyLine($possibleAuthorsElements[0]->plaintext) : "Unknown authors";
		$doi = sizeof($possibleDoiElements) > 0 ? $possibleDoiElements[0]->href : "Unknown doi";
		$abstract = sizeof($possibleAbstractElements) > 0 ? $possibleAbstractElements[0]->plaintext : "Unknown abstract";
		$pdfLink = "";
		
		foreach($possiblePdfLinksElements as $possiblePdfLink)
		{
			$linkContent = strtoupper(trim($possiblePdfLink->plaintext)); 
			if($linkContent == "PDF")
			{
				$pdfLink = $possiblePdfLink->href;
			}
		}
		
		echo("Title: $title<br/>");
		echo("Authors: $authors<br/>");
		echo("Doi: $doi<br/>");
		echo("Abstract: $abstract<br/>");
		echo("Pdf: $pdfLink<br/>");
		
		
		echo("<br/>**********<br/>");
	}
}

getGoogleData();