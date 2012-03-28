<?php
/**
 * Created by Jomaras.
 * Date: 28.03.12.@11:39
 */

require_once("php/helpers/FileAccess.php");
require_once("php/helpers/DatabaseAccess.php");

class MrtcPaper
{
    public function __construct($id, $title, $year, $source, $authors)
    {
        $this->_id = $id;
        $this->_title = $title;
        $this->_year = $year;
        $this->_source = $source;
        $this->_authors = $authors;
    }

    private $_id;
    public function GetId() { return $this->_id; }

    private $_title;
    public function GetTitle() { return $this->_title; }

    private $_year;
    public function GetYear() { return $this->_year; }

    private $_source;
    public function GetSource() { return $this->_source; }

    private $_authors;
    public function GetAuthors() { return $this->_authors; }

    public static function GetPapersFromJson()
    {
        $papersJSON = json_decode(FileAccess::GetFileContent("data/mrtcPages/merged.txt"));
        $papers = array();

        foreach($papersJSON as $paper)
        {
            $authors = array();

            foreach($paper->internalAuthors as $author)
            {
                $authors[] = new MrtcAuthor($author->name, $author->link);
            }

            foreach($paper->externalAuthors as $author)
            {
                if($author->name != "(former)")
                {
                    $authors[] = new MrtcAuthor($author->name, "");
                }
            }

            $papers[] = new MrtcPaper(0, $paper->title, $paper->year, $paper->source, $authors);
        }

        return $papers;
    }

    public static function GetPapersFromDb()
    {
        $papersTable = DatabaseAccess::ExQuery("SELECT * FROM mrtcpapers;");

        $papers = array();

        foreach($papersTable as $paper)
        {
            $papers[] = new MrtcPaper(0, $paper[1], $paper[2], $paper[3], array());
        }

        return $papers;
    }
}

class MrtcAuthor
{
    public function __construct($name, $link)
    {
        $this->_name = $name;
        $this->_link = $link;
    }

    private $_name;
    public function getName() { return $this->_name; }

    private $_link;
    public function getLink() { return $this->_link;}
}