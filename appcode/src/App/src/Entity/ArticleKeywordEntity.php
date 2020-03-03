<?php
declare(strict_types=1);
namespace App\Entity;

class ArticleKeywordEntity extends EntityAbstract
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
     * @var string
     */
    protected $keyword;

    /**
     * Set ID
     * @param int $id
     * @return ArticleKeywordEntity
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
     * @return ArticleKeywordEntity
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
     * Set Keyword
     * @param string $keyword
     * @return ArticleKeywordEntity
     */
    public function setKeyword(string $keyword): self
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get Keyword
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }
}
