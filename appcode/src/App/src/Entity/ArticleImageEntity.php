<?php
declare(strict_types=1);
namespace App\Entity;

class ArticleImageEntity extends EntityAbstract
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
    protected $url;

    /**
     * @var int
     */
    protected $statusId;

    /**
     * Set ID
     * @param int $id
     * @return ArticleImageEntity
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
     * @return ArticleImageEntity
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
     * Set URL
     * @param string $url
     * @return ArticleImageEntity
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get URL
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set Status ID
     * @param int $statusId
     * @return ArticleImageEntity
     */
    public function setStatusId(int $statusId): self
    {
        $this->statusId = $statusId;
        return $this;
    }

    /**
     * Get Status ID
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }
}
