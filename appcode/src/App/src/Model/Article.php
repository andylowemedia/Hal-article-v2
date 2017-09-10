<?php
namespace App\Model;

class Article extends ModelAbstract
{
    protected $id;
    protected $slug;
    protected $title;
    protected $subtitle;
    protected $summary;
    protected $content;
    protected $author;
    protected $originalUrl;
    protected $sourceId;
    protected $articleTypeId;
    protected $publishDate;
    protected $date;
    protected $statusId;
    
    protected $source;
    protected $images;
    
    public function setId($id)
    {
        $id = (int) $id;
        if ($id === 0) {
            throw new ModelException('ID must be a valid number', 500);
        }
        $this->id = $id;
        return $this;
    }
    
    public function getId()
    {
        if ($this->id && !is_int($this->id)) {
            throw new ModelException('ID must be a valid number', 500);
        }
        return $this->id;
    }
    
    public function setSlug($slug)
    {
        if (!is_string($slug) || $slug === '') {
            throw new ModelException('Slug must be set as a string', 500);
        }
        $this->slug = $slug;
        return $this;
    }
    
    public function getSlug()
    {
        if (!is_string($this->slug) || $this->slug === '') {
            throw new ModelException('Slug must be set as a string', 500);
        }
        return $this->slug;
    }
    
    public function createSlug()
    {
        if (is_null($this->getTitle())) {
            throw new ModelException('Title needs to be set to create a slug', 500);
        }
        $titleSlug = strtolower(html_entity_decode($this->getTitle()));
        $slug = preg_filter('/[^a-zA-Z0-9]/', "-", $titleSlug);
        $this->setSlug($slug);
        return $this;
    }
    
    public function setTitle($title)
    {
        if (!is_string($title) || $title === '') {
            throw new ModelException('Title must be set as a string', 500);
        }
        $this->title = $title;
        return $this;
    }
    
    public function getTitle()
    {
        if (!is_string($this->title) || $this->title === '') {
            throw new ModelException('Title must be set as a string', 500);
        }
        return $this->title;
    }
    
    public function setSubtitle($subtitle)
    {
        if (!is_string($subtitle)) {
            throw new ModelException('Subtitle must be set as a string', 500);
        }
        $this->subtitle = $subtitle;
        return $this;
    }
    
    public function getSubtitle()
    {
        if ($this->subtitle && (!is_string($this->subtitle) || $this->subtitle === '')) {
            throw new ModelException('Subtitle must be set as a string', 500);
        }
        return $this->subtitle;
    }
    
    public function setSummary($summary)
    {
        if (!is_string($summary)) {
            throw new ModelException('Summary must be set as a string', 500);
        }
        $this->summary = $summary;
        return $this;
    }
    
    public function getSummary()
    {
        if (!is_string($this->summary)) {
            throw new ModelException('Summary must be set as a string', 500);
        }
        return $this->summary;
    }
    
    public function createSummary()
    {
        $content = $this->getContent();
        if (is_null($content)) {
            throw new ModelException('Content must be sent to create summary', 500);
        }
        $summary = str_replace("<br>", " ", $content);
        $summary = str_replace("<br />", " ", $summary);
        $summary = str_replace("<br/>", " ", $summary);
        
        $this->setSummary(substr(strip_tags($summary), 0, 150));
        return $this;
    }
    
    public function setContent($content)
    {
        if (!is_string($content) || $content === '') {
            throw new ModelException('Content must be set as a string', 500);
        }
        $this->content = $content;
        return $this;
    }
    
    public function getContent()
    {
        if (!is_string($this->content)) {
            throw new ModelException('Content must be set as a string', 500);
        }
        return $this->content;
    }
    
    public function setAuthor($author)
    {
        if (!is_string($author)) {
            throw new ModelException('Author must be set as a string', 500);
        }
        $this->author = $author;
        return $this;
    }
    
    public function getAuthor()
    {
        if (!is_string($this->author) || $this->author === '') {
            throw new ModelException('Author must be set as a string', 500);
        }
        return $this->author;
    }
    
    public function defaultAuthor()
    {
        if (!is_string($this->author) || $this->author === '') {
            $this->setAuthor('Unknown');
        }
        return $this;
    }
    
    public function setOriginalUrl($originalUrl)
    {
        $this->originalUrl = $originalUrl;
        return $this;
    }
    
    public function getOriginalUrl()
    {
        return $this->originalUrl;
    }
    
    public function setSourceId($sourceId)
    {
        $sourceId = (int) $sourceId;
        if ($sourceId === 0) {
            throw new ModelException('Source ID must be a valid number', 500);
        }

        $this->sourceId = $sourceId;
        return $this;
    }
    
    public function getSourceId()
    {
        if (!is_int($this->sourceId)) {
            throw new ModelException('Source ID must be a valid number', 500);
        }

        return $this->sourceId;
    }
    
    public function setArticleTypeId($articleTypeId)
    {
        $this->articleTypeId = $articleTypeId;
        return $this;
    }
    
    public function getArticleTypeId()
    {
        return $this->articleTypeId;
    }
    
    public function setPublishDate($publishDate)
    {
        if (!is_string($publishDate)) {
            throw new Models\ModelException('Date must be a string');
        }
        $this->publishDate = new \DateTime(str_replace('at', '', $publishDate));
        return $this;
    }
    
    public function getPublishDate($format = 'Y-m-d H:i:s')
    {
        if (!($this->publishDate instanceof \DateTime)) {
            throw new ModelException('Date must be a DateTime object');
        }
        return $this->publishDate->format($format);
    }
    
    public function setDate($date)
    {
        if (!is_string($date)) {
            throw new ModelException('Date must be a string');
        }
        $dateTime = new \DateTime($date);
        $this->date = $dateTime;
        return $this;
    }
    
    public function getDate($format = 'Y-m-d H:i:s')
    {
        if (is_null($this->date)) {
            $this->setDate(date($format));
        } elseif (!($this->date instanceof \DateTime)) {
            throw new ModelException('Date must be a DateTime object');
        }
        return $this->date->format($format);
    }
    
    public function setStatusId($statusId)
    {
        $statusId = (int) $statusId;
        if ($statusId === 0) {
            throw new ModelException('ID must be a valid number', 500);
        }
        $this->statusId = $statusId;
        return $this;
    }
    
    public function getStatusId()
    {
        if (!is_int($this->statusId)) {
            throw new ModelException('ID must be a valid number', 500);
        }
        return $this->statusId;
    }
    
    public function toArray() {
        $this->createSlug();
        $this->createSummary();
        $this->defaultAuthor();
        return parent::toArray();
    }
    
    public function toArraySql() {
        $this->createSlug();
        $this->createSummary();
        $this->defaultAuthor();
        return parent::toArraySql();
    }
}
