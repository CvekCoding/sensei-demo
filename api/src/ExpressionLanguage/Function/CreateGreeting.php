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
use App\ExpressionLanguage\FieldType\InputField;
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
        yield new InputField(name: 'name', type: new StringType());
    }

    public function getResult(): string
    {
        return Greeting::class;
    }
    public function compiler(): \Closure
    {
        return function (string $name): string {
            return sprintf('%s(%s)', self::NAME, $name);
        };
    }

    public function evaluator(): \Closure
    {
        return function (array $context, string $name): Greeting {
            $greeting = new Greeting($name);
            $this->em->persist($greeting);
            $this->em->flush();

            return $greeting;
        };
    }
}
