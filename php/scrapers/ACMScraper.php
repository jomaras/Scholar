<?php
/**
 * Created by Jomaras.
 * Date: 06.03.12.@21:05
 */

require_once("php/Paper/Paper.php");

require_once("php/domParser/simple_html_dom.php");
require_once("php/helpers/ResponseHelper.php");
require_once('php/simpletest/browser.php');

class ACMScraper
{
    public static function ScrapePapers($searchTerm)
    {
        $papers = array();

        ResponseHelper::DefineBrowserHeaders();

        $browser = self::_NavigateToSearchResults("");

        $html = file_get_html_from_string($browser->getContent());

        $paperLinks = $html->find("a[href^=citation]");

        if(sizeof($paperLinks) == 0) { return $papers; }

        $parentTable = self::_GetFirstAncestorElementOfType($paperLinks[0], "table");

        if($parentTable == null) { return $papers; }

        $parentTable = self::_GetFirstAncestorElementOfType($parentTable, "table");

        if($parentTable == null) { return $papers; }

        foreach($parentTable->children as $row)
        {
            $titles = $row->find("a[href^=citation]");

            if(sizeof($titles) == 0) { continue; }

            $authors = $row->find(".authors");
            $pdfLinks = $row->find("a[title=Pdf]");
            $abstracts = $row->find("div[class^=abstract]");

            $title = sizeof($titles) != 0 ? trim($titles[0]->plaintext) : "";
            $authors = sizeof($authors) != 0 ? trim($authors[0]->plaintext) : "";
            $abstract = sizeof($abstracts) != 0 ? trim($abstracts[0]->plaintext) : "";
            $pdfLink = sizeof($pdfLinks) != 0 ? trim($pdfLinks[0]->href) : "";
            $doi = "";

            $papers[] = new Paper($title, $authors, $abstract, $doi, $pdfLink);
        }

        return $papers;
    }

    public static function ScrapeTotalNumberOfResults($searchTerm)
    {
        $browser = self::_NavigateToSearchResults($searchTerm);
        $html = file_get_html_from_string($browser->getContent());

        $noOfResultsParagraph = null;
        foreach($html->find("p[style]") as $paragraph)
        {
            if(strpos($paragraph->plaintext, "Found"))
            {
                $noOfResultsParagraph = $paragraph;
                break;
            }
        }

        if($noOfResultsParagraph == null) { return -1; }

        $boldElements = $noOfResultsParagraph->find("b");

        return sizeof($boldElements) > 0 ? $boldElements[0]->plaintext : "-1";
    }


    public static function _NavigateToSearchResults($allofem = "test", $anyofem = "test1", $noneofem = "test2")
    {
        ResponseHelper::DefineBrowserHeaders();

        $browser = new SimpleBrowser();
        $browser->get("http://dl.acm.org/");
        $browser->clickLink("Advanced Search");

        $browser->setField("allofem", $allofem);
        $browser->setField("anyofem", $anyofem);
        $browser->setField("noneofem", $noneofem);

        $browser->click("Search");

        return $browser;
    }

    private static function _GetFirstAncestorElementOfType($element, $nodeName)
    {
        if($element == null) { return null; }
        if($element->parent == null) { return null; }

        $element = $element->parent;

        while($element != null)
        {
            if($element->tag == $nodeName)
            {
                return $element;
            }

            $element = $element->parent;
        }

        return null;
    }
}