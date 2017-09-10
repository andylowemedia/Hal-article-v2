<?php

namespace App\Model;

class FeaturedArticle extends ModelAbstract
{

    protected $id = null;

    protected $articleId = null;

    protected $siteId = null;
    
    protected $orderNo = 0;

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

    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;
        return $this;
    }

    public function getSiteId()
    {
        return $this->siteId;
    }
    
    public function setOrderNo($orderNo)
    {
        $this->orderNo = $orderNo;
        return $this;
    }
    
    public function getOrderNo()
    {
        return $this->orderNo;
    }
    
}

