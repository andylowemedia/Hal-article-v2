<?php
declare(strict_types=1);
namespace App\Model;

/**
 * Class ArticleImage
 * @package App\Model
 */
class ArticleImage extends ModelAbstract
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
     * @return ArticleImage
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
     * @return ArticleImage
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
     * Set URL
     * @param string $url
     * @return ArticleImage
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
     * @return ArticleImage
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
