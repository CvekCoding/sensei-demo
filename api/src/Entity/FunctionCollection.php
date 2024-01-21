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
class FunctionCollection
{
    public iterable $functions = [];

    #[ApiProperty(identifier: true)]
    public function getId(): string
    {
        return (string) Uuid::v7();
    }

    public function getFunctions(): iterable
    {
        yield from $this->functions;
    }

    public function insertFunctions(iterable $functions): FunctionCollection
    {
        $this->functions = $functions;

        return $this;
    }
}
