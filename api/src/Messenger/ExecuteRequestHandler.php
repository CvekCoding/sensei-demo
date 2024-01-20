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

namespace App\Messenger;

use App\Entity\ExecuteRequest;
use App\ExpressionLanguage\SenseiExpressionProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ExecuteRequestHandler
{
    public function __construct(private SenseiExpressionProvider $senseiExpressionProvider)
    {}

    public function __invoke(ExecuteRequest $request): ExecuteRequest
    {
        $expressionLanguage = new ExpressionLanguage();
        $expressionLanguage->registerProvider($this->senseiExpressionProvider);

        return $request->setResult($expressionLanguage->evaluate($request->getCommand(), ['request' => $request]) ?? null);
    }
}
