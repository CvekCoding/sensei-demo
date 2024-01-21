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

namespace App\ExpressionLanguage;

use App\ExpressionLanguage\Function\ExpressionFunctionInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final readonly class SenseiExpressionProvider implements ExpressionFunctionProviderInterface
{
    public function __construct(#[TaggedIterator(ExpressionFunctionInterface::TAG)] private iterable $functions)
    {}

    public function getFunctions(): iterable
    {
        /** @var ExpressionFunctionInterface $function */
        foreach ($this->functions as $function) {
            yield new ExpressionFunction(
                name: $function->getName(),
                compiler: $function->compiler(),
                evaluator: $function->evaluator()
            );
        }
    }
}
