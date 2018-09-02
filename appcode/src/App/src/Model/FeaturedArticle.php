<?php
declare(strict_types=1);
namespace App\Model;

/**
 * Class FeaturedArticle
 * @package App\Model
 */
class FeaturedArticle extends ModelAbstract
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $articleId;

    /**
     * @var int
     */
    protected $siteId;

    /**
     * @var int
     */
    protected $orderNo = 0;

    /**
     * Set ID
     * @param int $id
     * @return FeaturedArticle
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set Article ID
     * @param int $articleId
     * @return FeaturedArticle
     */
    public function setArticleId(int $articleId): self
    {
        $this->articleId = $articleId;
        return $this;
    }

    /**
     * Get Article ID
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * Set Site ID
     * @param int $siteId
     * @return FeaturedArticle
     */
    public function setSiteId(int $siteId): self
    {
        $this->siteId = $siteId;
        return $this;
    }

    /**
     * Get Site ID
     * @return int
     */
    public function getSiteId(): int
    {
        return $this->siteId;
    }

    /**
     * Set Order No
     * @param int $orderNo
     * @return FeaturedArticle
     */
    public function setOrderNo(int $orderNo): self
    {
        $this->orderNo = $orderNo;
        return $this;
    }

    /**
     * Get Order No
     * @return int
     */
    public function getOrderNo(): int
    {
        return $this->orderNo;
    }
}
