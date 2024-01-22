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
use App\ExpressionLanguage\FieldType\StringType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class FindPerson implements ExpressionFunctionInterface
{
    public const NAME = 'FIND_PERSON';

    public function __construct(private PersonRepository $personRepository)
    {}

    public function getName(): string
    {
        return self::NAME;
    }

    public function getInputFields(): iterable
    {
        yield new InputField(name: 'name', type: new StringType(), required: true);
    }

    public function getResult(): string
    {
        return Person::class;
    }
    public function compiler(): \Closure
    {
        return fn(string $name): string => sprintf('%s(%s)', self::NAME, $name);
    }

    public function evaluator(): \Closure
    {
        return fn(array $context, string $name): ?Person => $this->personRepository->findOneBy(['name' => $name]);
    }
}
