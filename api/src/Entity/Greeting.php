<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(mercure: true)]
#[ORM\Entity]
class Greeting
{
    /**
     * The entity ID
     */
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue]
    public readonly ?int $id;

    public function __construct(
        #[ORM\Column] #[Assert\NotBlank] private string $name,
        #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: 'greetings')] public Person $person,
        #[ORM\Column(nullable: true)] public readonly ?int $number = null
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Greeting
    {
        $this->name = $name;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setPerson(Person $person): Greeting
    {
        $this->person = $person;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }
}
