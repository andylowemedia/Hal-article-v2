<?php
namespace App\Model;

class DisplayArticle extends ModelAbstract
{
    protected $slug;
    protected $title;
    protected $subtitle;
    protected $summary;
    protected $author;
    protected $url;
    protected $publishDate;
    protected $source;
    protected $image;
    protected $displayCategories;
    protected $keywords;
    
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
        $this->subtitle = (string) $subtitle;
        return $this;
    }
    
    public function getSubtitle()
    {
        return (string) $this->subtitle;
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
        return $this->author;
    }
    
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
    
    public function getUrl()
    {
        return $this->url;
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
    
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }
    
    public function getSource()
    {
        return $this->source;
    }
    
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }
    
    public function getImage()
    {
        return $this->image;
    }
    
    public function setDisplayCategories($displayCategories)
    {
        $this->displayCategories = $displayCategories;
        return $this;
    }
    
    public function getDisplayCategories()
    {
        return $this->displayCategories;
    }
    
    public function setKeywords($keywoods)
    {
        $this->keywords = array_filter($keywoods);
        return $this;
    }
    
    public function getKeywords()
    {
        return $this->keywords;
    }
}
