<?php

namespace App\Model;

class ArticleKeyword extends ModelAbstract
{

    protected $id = null;

    protected $articleId = null;

    protected $keyword = null;

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

    public function setKeyword(string $keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }


}

