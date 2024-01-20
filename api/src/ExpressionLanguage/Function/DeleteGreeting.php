<?php
/*
 * This file is part of the Aqua Delivery package.
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
use App\ExpressionLanguage\FieldType\IntType;
use App\ExpressionLanguage\FieldType\NullType;
use App\ExpressionLanguage\FieldType\StringType;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DeleteGreeting implements ExpressionFunctionInterface
{
    public const NAME = 'DELETE_GREETING';

    public function __construct(private EntityManagerInterface $em)
    {}

    public function getName(): string
    {
        return self::NAME;
    }

    public function getInputFields(): iterable
    {
        yield new InputField(name: 'id', type: new IntType());
    }

    public function getResult(): string
    {
        return NullType::NAME;
    }

    public function compiler(): \Closure
    {
        return function (string $id): string {
            return sprintf('%s(%s)', self::NAME, $id);
        };
    }

    public function evaluator(): \Closure
    {
        return function (array $context, int $id): void {
            $greeting = $this->em->find(Greeting::class, $id);
            if (isset($greeting)) {
                $this->em->remove($greeting);
                $this->em->flush();
            }
        };
    }
}
