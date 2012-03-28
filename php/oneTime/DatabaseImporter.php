<?php
/**
 * Created by Jomaras.
 * Date: 28.03.12.@11:29
 */

require_once("php/oneTime/MrtcPaper.php");
require_once("php/helpers/DatabaseAccess.php");

class DatabaseImporter
{
    public static function ImportMrtcPapers()
    {
        $papers = MrtcPaper::GetPapersFromJson();

        foreach($papers as $paper)
        {
            $title = $paper->GetTitle();
            $year = $paper->GetYear();
            $source = $paper->GetSource();

            DatabaseAccess::ExQuery("INSERT INTO mrtcpapers (Title,Year,Source) VALUES('$title', $year, '$source');");
        }
    }

    public static function ImportMrtcAuthors()
    {
        $papers = MrtcPaper::GetPapersFromJson();
        $authors = self::_GetUniqueAuthors($papers);

        foreach($authors as $author)
        {
            $name = $author->GetName();
            $link = $author->GetLink();
            DatabaseAccess::ExQuery("INSERT INTO mrtcauthors (Name, Link) VALUES('$name', '$link');");
        }
    }

    public static function LinkPapersAndAuthors()
    {
        set_time_limit(0);
        $papers = MrtcPaper::GetPapersFromJson();
        $uniqueAuthors = self::_GetUniqueAuthors($papers);

        $paperId = 1;
        foreach($papers as $paper)
        {
            $authors = $paper->GetAuthors();
            foreach($authors as $author)
            {
                $authorId = self::_GetAuthorIndex($uniqueAuthors, $author);
                DatabaseAccess::ExQuery("INSERT INTO mrtcpaperauthors (AuthorID, PaperID) VALUES($authorId, $paperId);");
            }
            $paperId++;
        }
    }

    private static function _GetAuthorIndex($authors, $author)
    {
        $index = 1;

        foreach($authors as $currentAuthor)
        {
            if($currentAuthor->GetName() == $author->GetName()) { return $index; }

            $index++;
        }

        return -1;
    }

    private static function _GetUniqueAuthors($papers)
    {
        $uniqueAuthors = array();

        foreach($papers as $paper)
        {
            $authors = $paper->GetAuthors();

            foreach($authors as $author)
            {
                if(!self::_ContainsAuthor($uniqueAuthors, $author))
                {
                    $uniqueAuthors[] = $author;
                }
            }
        }

        return $uniqueAuthors;
    }

    private static function _ContainsAuthor($authors, $author)
    {
        foreach($authors as $currentAuthor)
        {
            if($currentAuthor->GetName() == $author->GetName()) { return true; }
        }

        return false;
    }
}