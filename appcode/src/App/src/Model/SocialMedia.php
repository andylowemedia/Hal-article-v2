<?php
declare(strict_types=1);

namespace App\Model;


class SocialMedia extends ModelAbstract
{
    protected $id;
    protected $name;

    public function setId($id): self
    {
        $this->id = (int) $id;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
