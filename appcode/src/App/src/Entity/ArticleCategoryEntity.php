<?php
declare(strict_types=1);

namespace App\Entity;

class ArticleCategoryEntity extends EntityAbstract
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
    protected $categoryId;

    /**
     * Set ID
     * @param $id
     * @return ArticleCategoryEntity
     */
    public function setId($id): self
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
     * Set article ID
     * @param $articleId
     * @return ArticleCategoryEntity
     */
    public function setArticleId($articleId): self
    {
        $this->articleId = (int) $articleId;
        return $this;
    }

    /**
     * Get article ID
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * Set CategoryMapper ID
     * @param $categoryId
     * @return ArticleCategoryEntity
     */
    public function setCategoryId($categoryId): self
    {
        $this->categoryId = (int) $categoryId;
        return $this;
    }

    /**
     * Get CategoryMapper ID
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
}
