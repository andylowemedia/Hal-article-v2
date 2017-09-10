<?php

namespace App\Model;

class ArticleCategory extends ModelAbstract
{

    protected $id = null;

    protected $articleId = null;

    protected $categoryId = null;

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

    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getCategoryId()
    {
        return $this->categoryId;
    }


}

