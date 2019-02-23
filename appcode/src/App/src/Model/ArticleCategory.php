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
     * @param $id
     * @return ArticleCategory
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
     * @return ArticleCategory
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
     * Set Category ID
     * @param $categoryId
     * @return ArticleCategory
     */
    public function setCategoryId($categoryId): self
    {
        $this->categoryId = (int) $categoryId;
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
