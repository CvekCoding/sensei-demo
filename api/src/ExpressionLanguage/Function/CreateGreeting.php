<?php
/*
 * This file is part of the Sensei package.
 *
 * (c) Sergey Logachev <svlogachev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\ExpressionLanguage\Function;

use App\Entity\Greeting;
use App\Entity\Person;
use App\ExpressionLanguage\FieldType\InputField;
use App\ExpressionLanguage\FieldType\IntType;
use App\ExpressionLanguage\FieldType\PersonType;
use App\ExpressionLanguage\FieldType\StringType;
use Doctrine\ORM\EntityManagerInterface;

final readonly class CreateGreeting implements ExpressionFunctionInterface
{
    public const NAME = 'CREATE_GREETING';

    public function __construct(private EntityManagerInterface $em)
    {}

    public function getName(): string
    {
        return self::NAME;
    }

    public function getInputFields(): iterable
    {
        yield new InputField(name: 'name', type: new StringType(), required: true);
        yield new InputField(name: 'person', type: new PersonType(), required: true);
        yield new InputField(name: 'number', type: new IntType(), required: false);
    }

    public function getResult(): string
    {
        return Greeting::class;
    }
    public function compiler(): \Closure
    {
        return fn(string $name, Person $person, ?int $number = null) => sprintf('%s(%s, %u)', self::NAME, $name, $number);
    }

    public function evaluator(): \Closure
    {
        return function (array $context, string $name, Person $person, ?int $number = null): Greeting {
            $greeting = new Greeting($name, $person, $number);
            $this->em->persist($greeting);

            return $greeting;
        };
    }
}
