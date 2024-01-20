<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\ExpressionLanguage\Function\ExpressionFunctionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(operations: [
    new Get(status: 404, controller: NotFoundAction::class, read: false),
    new Post(messenger: true),
])]
class ExecuteRequest
{
    private mixed $result;

    public function __construct(private string $command)
    {}

    #[ApiProperty(identifier: true)]
    public function getId(): ?string
    {
        return Uuid::v7();
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function setResult(mixed $result)
    {
        $this->result = $result;

        return $this;
    }
}
