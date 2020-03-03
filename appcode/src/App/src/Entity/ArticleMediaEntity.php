<?php
declare(strict_types=1);
namespace App\Entity;

class ArticleMediaEntity extends EntityAbstract
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
    protected $code;

    /**
     * @var int
     */
    protected $statusId;

    /**
     * Set ID
     * @param int $id
     * @return ArticleMediaEntity
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
     * @return ArticleMediaEntity
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
     * Set Code
     * @param string $code
     * @return ArticleMediaEntity
     */
    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Get Code
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Set Status ID
     * @param int $statusId
     * @return ArticleMediaEntity
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
