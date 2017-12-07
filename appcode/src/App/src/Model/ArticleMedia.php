<?php

namespace App\Model;

class ArticleMedia extends ModelAbstract
{

    protected $id;
    protected $articleId;
    protected $code;    
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

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
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

