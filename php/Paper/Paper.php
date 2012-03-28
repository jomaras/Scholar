<?php
/**
 * Created by Jomaras.
 * Date: 06.03.12.@20:10
 */
class Paper
{
   public function __construct($title, $authors, $abstract, $doi, $pdfLink)
   {
       $this->_title = $title;
       $this->_authors = $authors;
       $this->_abstract = $abstract;
       $this->_doi = $doi;
       $this->_pdfLink = $pdfLink;

       $this->_referencedPapers = array();
       $this->_referencedByPapers = array();
   }

    private $_id;
    public function GetId() { return $this->_id; }

    private $_title;
    public function GetTitle() { return $this->_title; }

    private $_authors;
    public function GetAuthors() { return $this->_authors; }

    private $_abstract;
    public function GetAbstract() { return $this->_abstract; }

    private $_doi;
    public function GetDoi() { return $this->_doi; }

    private $_pdfLink;
    public function GetPdfLink() { return $this->_pdfLink; }

    private $_referencedPapers;
    public function GetReferencedPapers() { return $this->_referencedPapers; }

    private $_referencedByPapers;
    public function GetReferencedByPapers() { return $this->_referencedByPapers; }
}
