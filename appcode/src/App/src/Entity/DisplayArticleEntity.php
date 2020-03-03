<?php
declare(strict_types=1);
namespace App\Entity;

class DisplayArticleEntity extends EntityAbstract
{
    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $slug = '';

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $subtitle = '';

    /**
     * @var string
     */
    protected $summary = '';

    /**
     * @var string
     */
    protected $author = '';

    /**
     * @var string
     */
    protected $url = '';

    /**
     * @var \DateTime
     */
    protected $publishDate;

    /**
     * @var string
     */
    protected $source = '';

    /**
     * @var string
     */
    protected $image = '';

    /**
     * @var array
     */
    protected $displayCategories = [];

    /**
     * @var array
     */
    protected $keywords = [];

    /**
     * @var array
     */
    protected $posted = [];

    /**
     * Set article ID
     * @param int $id
     * @return DisplayArticleEntity
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get article ID
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set slug
     * @param string $slug
     * @return DisplayArticleEntity
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set title
     * @param string $title
     * @return DisplayArticleEntity
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get Title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set subtitle
     * @param string $subtitle
     * @return DisplayArticleEntity
     */
    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = (string) $subtitle;
        return $this;
    }

    /**
     * Get subtitle
     * @return string
     */
    public function getSubtitle(): string
    {
        return $this->subtitle;
    }

    /**
     * Set summary
     * @param string $summary
     * @return DisplayArticleEntity
     */
    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * Get summary
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Set author
     * @param string $author
     * @return DisplayArticleEntity
     */
    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Get author
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Set url
     * @param string $url
     * @return DisplayArticleEntity
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Get url
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set publish date
     * @param string $publishDate
     * @return DisplayArticleEntity
     */
    public function setPublishDate(string $publishDate): self
    {
        $this->publishDate = new \DateTime(str_replace('at', '', $publishDate));
        return $this;
    }

    /**
     * Get publish date
     * @param string $format
     * @return string
     */
    public function getPublishDate(string $format = 'Y-m-d H:i:s'): string
    {
        return $this->publishDate->format($format);
    }

    /**
     * Set source
     * @param string $source
     * @return DisplayArticleEntity
     */
    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get source
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Set image
     * @param string $image
     * @return DisplayArticleEntity
     */
    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Set Display Categories
     * @param array $displayCategories
     * @return DisplayArticleEntity
     */
    public function setDisplayCategories(array $displayCategories): self
    {
        $this->displayCategories = $displayCategories;
        return $this;
    }

    /**
     * Get Display Categories
     * @return array
     */
    public function getDisplayCategories(): ?array
    {
        return $this->displayCategories;
    }

    /**
     * Set Keywords
     * @param array $keywords
     * @return DisplayArticleEntity
     */
    public function setKeywords(array $keywords): self
    {
        $this->keywords = array_filter($keywords);
        return $this;
    }

    /**
     * Get Keywords
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }

    public function setPosted(array $posted): self
    {
        $this->posted = $posted;
        return $this;
    }

    public function getPosted(): ?array
    {
        return $this->posted;
    }
}
