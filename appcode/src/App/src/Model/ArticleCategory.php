<?php
declare(strict_types=1);
namespace App\Model;

/**
 * Class ArticleCategory
 * @package App\Model
 */
class ArticleCategory extends ModelAbstract
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
     * @param int $id
     * @return ArticleCategory
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
     * Set article ID
     * @param int $articleId
     * @return ArticleCategory
     */
    public function setArticleId(int $articleId): self
    {
        $this->articleId = $articleId;
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
     * Set Category ID
     * @param int $categoryId
     * @return ArticleCategory
     */
    public function setCategoryId(int $categoryId): self
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * Get Category ID
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
}
