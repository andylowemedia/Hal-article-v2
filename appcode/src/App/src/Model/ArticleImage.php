<?php

namespace App\Model;

class ArticleImage extends ModelAbstract
{

    protected $id;
    protected $articleId;
    protected $url;    
    protected $statusId;

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setArticleId($articleId)
    {
        $this->articleId = $articleId;
        return $this;
    }

    public function getArticleId()
    {
        return $this->articleId;
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
    
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;
        return $this;
    }
    
    public function getStatusId()
    {
        return $this->statusId;
    }
}

