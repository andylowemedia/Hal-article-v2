<?php

declare(strict_types=1);

namespace App\Model;


class ArticleSocialMediaPost extends ModelAbstract
{
    protected $id;
    protected $articleId;
    protected $siteId;
    protected $socialMediaId;
    protected $postedDatetime;

    public function setId($id): self
    {
        $this->id = (int) $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setArticleId($articleId): self
    {
        $this->articleId = (int) $articleId;
        return $this;
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function setSiteId($siteId): self
    {
        $this->siteId = (int) $siteId;
        return $this;
    }

    public function getSiteId(): int
    {
        return $this->siteId;
    }

    public function setSocialMediaId($socialMediaId): self
    {
        $this->socialMediaId = (int) $socialMediaId;
        return $this;
    }

    public function getSocialMediaId(): int
    {
        return $this->socialMediaId;
    }

    public function setPostedDatetime(string $postedDatetime): self
    {
        $this->postedDatetime = new \DateTime($postedDatetime);
        return $this;
    }

    public function getPostedDatetime(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->postedDatetime->format($format);
    }
}
