<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Symfony\Component\Uid\Uuid;

#[ApiResource(operations: [
    new Get(status: 404, controller: NotFoundAction::class, read: false),
    new Post(messenger: true),
])]
class ExecuteRequest
{
    private mixed $result;
    private ?string $compiled = null;
    private ?string $toExecute = null;

    public function __construct(public readonly string $command, public readonly ?Context $context = null)
    {
        $this->toExecute = $this->command;
    }

    #[ApiProperty(identifier: true)]
    public function getId(): ?string
    {
        return Uuid::v7();
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function setResult(mixed $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getCompiled(): ?string
    {
        return $this->compiled;
    }

    public function setCompiled(string $compiled): ExecuteRequest
    {
        $this->compiled = $compiled;

        return $this;
    }

    public function setToExecute(string $toExecute): ExecuteRequest
    {
        $this->toExecute = $toExecute;

        return $this;
    }

    public function getToExecute(): string
    {
        return $this->toExecute;
    }
}
