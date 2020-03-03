<?php
declare(strict_types=1);
namespace App\Entity;

class FeaturedArticleEntity extends EntityAbstract
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
     * @return FeaturedArticleEntity
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
     * Set ArticleMapper ID
     * @param int $articleId
     * @return FeaturedArticleEntity
     */
    public function setArticleId(int $articleId): self
    {
        $this->articleId = $articleId;
        return $this;
    }

    /**
     * Get ArticleMapper ID
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * Set Site ID
     * @param int $siteId
     * @return FeaturedArticleEntity
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
     * @return FeaturedArticleEntity
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
