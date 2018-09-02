<?php
declare(strict_types=1);
namespace App\Model;

/**
 * Class Category
 * @package App\Model
 */
class Category extends ModelAbstract
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $fullPath;

    /**
     * @var int
     */
    protected $parentId;

    /**
     * @var bool
     */
    protected $baseLevel;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $statusId;

    /**
     * Set ID
     * @param int $id
     * @return Category
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
     * Set Full Path
     * @param string $fullPath
     * @return Category
     */
    public function setFullPath(string $fullPath): self
    {
        $this->fullPath = $fullPath;
        return $this;
    }

    /**
     * Get Full Path
     * @return string
     */
    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    /**
     * Set Parent ID
     * @param int $parentId
     * @return Category
     */
    public function setParentId(int $parentId): self
    {
        $this->parentId = $parentId;
        return $this;
    }

    /**
     * Get Parent ID
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * Set Base Level
     * @param bool $baseLevel
     * @return Category
     */
    public function setBaseLevel(bool $baseLevel): self
    {
        $this->baseLevel = $baseLevel;
        return $this;
    }

    /**
     * Get Base Level
     * @return bool
     */
    public function getBaseLevel(): bool
    {
        return $this->baseLevel;
    }

    /**
     * Set Code
     * @param string $code
     * @return Category
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
     * Set Name
     * @param string $name
     * @return Category
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get Name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set Status ID
     * @param int $statusId
     * @return Category
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
