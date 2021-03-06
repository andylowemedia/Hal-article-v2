<?php
declare(strict_types=1);
namespace App\Entity;

class SourceHistoryEntity extends EntityAbstract
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $sourceId;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var int
     */
    protected $statusId;

    /**
     * @var string
     */
    protected $date;

    /**
     * Set ID
     * @param int $id
     * @return SourceHistoryEntity
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
     * Set Source ID
     * @param int $sourceId
     * @return SourceHistoryEntity
     */
    public function setSourceId(int $sourceId): self
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * Get Source ID
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * Set URL
     * @param string $url
     * @return SourceHistoryEntity
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
     * Set Message
     * @param string $message
     * @return SourceHistoryEntity
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get Message
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set Status ID
     * @param int $statusId
     * @return SourceHistoryEntity
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

    /**
     * Set Date
     * @param string $date
     * @return SourceHistoryEntity
     */
    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get Date
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }
}
