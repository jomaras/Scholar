<?php
/**
 * Created by Jomaras.
 * Date: 28.03.12.@21:52
 */
require_once("php/oneTime/MrtcPaper.php");
require_once("php/helpers/DatabaseAccess.php");

require_once("php/domParser/simple_html_dom.php");
require_once("php/helpers/ResponseHelper.php");
require_once('php/simpletest/browser.php');

class GoogleScholarSearch
{
    public static function Search()
    {
        set_time_limit(0);
        $papers = MrtcPaper::GetPapersFromDb();

        $index = 1;
        $browser = new SimpleBrowser();
        ResponseHelper::DefineBrowserHeaders();

        foreach($papers as $paper)
        {
            $title = $paper->GetTitle();
            $browser->get("http://scholar.google.hr/scholar?q=%22" . $title ."%22&hl=hr&num=100&btnG=Tra%C5%BEi");
            $escapedHtml = mysql_real_escape_string($browser->getContent());

            DatabaseAccess::ExQuery("INSERT INTO mrtcscholarresults (PaperID, Title, GoogleSearchResult) VALUES ($index, '$title', '$escapedHtml');");

            $index++;
            if($index == 10) { break; }
        }
    }

    public static function MineDownloadedResults()
    {
        $downloadedPapers = DatabaseAccess::ExQuery('SELECT * FROM mrtcscholarresults WHERE GoogleSearchResult IS NOT NULL AND GoogleFoundTitle =  "";');

        foreach($downloadedPapers as $row)
        {
            $title = $row[2];
            $html = $row[3];

           // $htmlDom = file_get_html_from_string($html);
            echo($html);
            break;
        }
    }

}