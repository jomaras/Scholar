<?php
/**
 * Created by Jomaras.
 * Date: 07.03.12.@19:16
 */

require_once("php/Paper/Paper.php");

require_once("php/domParser/simple_html_dom.php");
require_once("php/helpers/ResponseHelper.php");
require_once('php/simpletest/browser.php');

class GoogleScholarScraper
{
    public static function ScrapePapers($paperTitle)
    {
        $papers = array();

        ResponseHelper::DefineBrowserHeaders();

        $browser = new SimpleBrowser();
        $browser->get(self::_GetSearchPaperUrl($paperTitle));
    }

    public static function _GetSearchPaperUrl($paperTitle)
    {
        return "http://scholar.google.hr/scholar?q=%22" . $paperTitle ."%22&hl=hr&btnG=Tra%C5%BEi";
    }
}