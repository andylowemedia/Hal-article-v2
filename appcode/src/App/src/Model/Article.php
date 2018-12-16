<?php
declare(strict_types=1);
namespace App\Model;

class Article extends ModelAbstract
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $subtitle;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $originalUrl;

    /**
     * @var int
     */
    protected $sourceId;

    /**
     * @var int
     */
    protected $articleTypeId;

    /**
     * @var string
     */
    protected $publishDate;

    /**
     * @var string
     */
    protected $date;

    /**
     * @var int
     */
    protected $statusId;

    /**
     * Set Id
     * @param $id
     * @return Article
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get Id
     * @return int|null
     */
    public function getId(): ?int
    {
        return (int) $this->id;
    }

    /**
     * Set Slug
     * @param string $slug
     * @return Article
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get Slug
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Creates slug based on title
     * @return Article
     * @throws ModelException
     */
    public function createSlug(): self
    {
        if (empty($this->getTitle())) {
            throw new ModelException('Title needs to be set to create a slug', 500);
        }
        $titleSlug = strtolower(html_entity_decode($this->getTitle()));
        $slug = preg_filter('/[^a-zA-Z0-9]/', "-", $titleSlug);
        $this->setSlug(strtolower($slug));
        return $this;
    }

    /**
     * Sets title
     * @param string $title
     * @return Article
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Sets subtitle
     * @param string $subtitle
     * @return Article
     */
    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    /**
     * Gets subtitle
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * Sets summary
     * @param string $summary
     * @return Article
     */
    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Gets summary
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Creates summary based on content
     * @return Article
     * @throws ModelException
     */
    public function createSummary(): self
    {
        $content = $this->getContent();
        if (empty($content)) {
            throw new ModelException('Content must be sent to create summary', 500);
        }
        $summary = str_replace("<br>", " ", $content);
        $summary = str_replace("<br />", " ", $summary);
        $summary = str_replace("<br/>", " ", $summary);

        $this->setSummary(substr(strip_tags($summary), 0, 150));
        return $this;
    }

    /**
     * Set Content
     * @param string $content
     * @return Article
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get Content
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set Author
     * @param string $author
     * @return Article
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get Author
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Creates default author
     * @return Article
     */
    public function defaultAuthor(): self
    {
        if (!is_string($this->author) || $this->author === '') {
            $this->setAuthor('Unknown');
        }
        return $this;
    }

    /**
     * Sets original url
     * @param string $originalUrl
     * @return Article
     */
    public function setOriginalUrl(string $originalUrl): self
    {
        $this->originalUrl = $originalUrl;
        return $this;
    }

    /**
     * Gets original url
     * @return string
     */
    public function getOriginalUrl(): string
    {
        return $this->originalUrl;
    }

    /**
     * Sets source ID
     * @param int $sourceId
     * @return Article
     */
    public function setSourceId(int $sourceId): self
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * Gets source ID
     * @return int
     */
    public function getSourceId(): int
    {
        return $this->sourceId;
    }

    /**
     * Sets article type ID
     * @param int $articleTypeId
     * @return Article
     */
    public function setArticleTypeId(int $articleTypeId): self
    {
        $this->articleTypeId = $articleTypeId;
        return $this;
    }

    /**
     * Gets article type ID
     * @return int
     */
    public function getArticleTypeId(): int
    {
        return $this->articleTypeId;
    }

    /**
     * Sets publish date
     * @param string $publishDate
     * @return Article
     */
    public function setPublishDate(string $publishDate): self
    {
        $this->publishDate = new \DateTime(str_replace('at', '', $publishDate));
        return $this;
    }

    /**
     * Gets publish date
     * @param string $format
     * @return string
     */
    public function getPublishDate(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->publishDate->format($format);
    }

    /**
     * Sets date
     * @param string $date
     * @return Article
     */
    public function setDate(string $date): self
    {
        $dateTime = new \DateTime($date);
        $this->date = $dateTime;
        return $this;
    }

    /**
     * Gets date
     * @param string $format
     * @return string
     */
    public function getDate(string $format = 'Y-m-d H:i:s'): string
    {
        if (is_null($this->date)) {
            $this->setDate(date($format));
        }
        return $this->date->format($format);
    }

    /**
     * Sets status ID
     * @param int $statusId
     * @return Article
     */
    public function setStatusId(int $statusId): self
    {
        $this->statusId = $statusId;
        return $this;
    }

    /**
     * Gets status ID
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->statusId;
    }

    /**
     * Converts to array
     * @return array
     * @throws ModelException
     */
    public function toArray(): array
    {
        $this->createSlug();
        $this->createSummary();
        $this->defaultAuthor();
        return parent::toArray();
    }

    /**
     * Converts to array for SQL
     * @return array
     * @throws ModelException
     */
    public function toArraySql(): array
    {
        $this->createSlug();
        $this->createSummary();
        $this->defaultAuthor();
        return parent::toArraySql();
    }
}
