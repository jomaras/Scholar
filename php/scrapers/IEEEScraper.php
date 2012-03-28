<?php
/**
 * Created by Jomaras.
 * Date: 06.03.12.@20:09
 */

require_once("php/Paper/Paper.php");

require_once("php/domParser/simple_html_dom.php");
require_once("php/helpers/ResponseHelper.php");
require_once('php/simpletest/browser.php');

class IEEEScraper
{
    public static function ScrapePapers($searchTerm)
    {
        $papers = array();

        ResponseHelper::DefineBrowserHeaders();

        $browser = new SimpleBrowser();
        $browser->get(self::_GetSearchUrl($searchTerm));

        $html = file_get_html_from_string($browser->getContent());

        foreach($html->find('.Results li') as $searchResultElement)
        {
            $possibleTitleElements = $searchResultElement->find("h3 a");
            $possibleAuthorsElements = $searchResultElement->find("p");
            $possibleDoiElements = $searchResultElement->find("a[href^=http://dx.doi.org/]");
            $possibleAbstractElements = $searchResultElement->find(".abstract");
            $possiblePdfLinksElements = $searchResultElement->find(".links a");


            $title = sizeof($possibleTitleElements) > 0 ? $possibleTitleElements[0]->plaintext : "Unknown title";
            $authors = sizeof($possibleAuthorsElements) > 0 ? self::_FindFirstNonEmptyLine($possibleAuthorsElements[0]->plaintext) : "Unknown authors";
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

            $papers[] = new Paper($title, $authors, $abstract, $doi, $pdfLink);
        }

        return $papers;
    }

    public static function ScrapeTotalNumberOfResults($searchTerm)
    {
        ResponseHelper::DefineBrowserHeaders();

        $browser = new SimpleBrowser();
        $browser->get(self::_GetSearchUrl($searchTerm));

        $html = file_get_html_from_string($browser->getContent());

        $potentialResultsElement = $html->find('".results-display .display-status"');

        $resultElement = sizeof($potentialResultsElement) > 0 ? $potentialResultsElement[0]
                                                              : null;
        if($resultElement == null) { return -1; }

        //should be in format: Showing 1 - 25 of 209.687 results
        $resultsText = $resultElement->plaintext;

        $parts = explode("of", $resultsText);

        if(sizeof($parts) < 2) { return -1; }

        //should be in format: 209.687 results
        $resultsString = $parts[1];
        return "Results:" .  trim(str_replace("results", "", $resultsString));
    }

    private static function _GetSearchUrl($searchString)
    {
        $baseUrl =  'http://ieeexplore.ieee.org/search/searchresult.jsp?action=search&';
        $additionalParameters = "rowsPerPage=100&searchField=Search%20All%20Text&matchBoolean=true&";
        $queryTextAttribute = "queryText=((.QT.test%20case.QT.)%20AND%20.QT.unit.QT.)";

        return $baseUrl . $additionalParameters . $queryTextAttribute;
    }

    private static function _FindFirstNonEmptyLine($string)
    {
        foreach(explode("\n", $string) as $line)
        {
            if(trim($line) != "") { return $line; }
        }

        return "";
    }
}
