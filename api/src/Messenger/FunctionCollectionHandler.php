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

namespace App\Messenger;

use App\Entity\FunctionCollection;
use App\ExpressionLanguage\Function\ExpressionFunctionInterface;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class FunctionCollectionHandler
{
    public function __construct(#[TaggedIterator(ExpressionFunctionInterface::TAG)] private iterable $functions)
    {}

    public function __invoke(FunctionCollection $collection): FunctionCollection
    {
        return $collection->insertFunctions($this->functions);
    }
}
