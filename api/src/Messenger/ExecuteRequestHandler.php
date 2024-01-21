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

use ApiPlatform\Validator\Exception\ValidationException;
use App\Entity\ExecuteRequest;
use App\ExpressionLanguage\SenseiExpressionProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ExecuteRequestHandler
{
     private ExpressionLanguage $expressionLanguage;

    public function __construct(private SenseiExpressionProvider $senseiExpressionProvider, private EntityManagerInterface $em)
    {
        $this->expressionLanguage = new ExpressionLanguage(providers: [$this->senseiExpressionProvider]);
    }

    public function __invoke(ExecuteRequest $request): ExecuteRequest
    {
        try {
            $this->expressionLanguage->lint(expression: $request->getCommand(), names: null);
        } catch (SyntaxError $e) {
            throw new ValidationException(message: $e->getMessage(), previous: $e);
        }

        $request->setCompiled($this->expressionLanguage->compile($request->getCommand()));
        $request->setResult($this->expressionLanguage->evaluate($request->getCommand(), ['request' => $request]) ?? null);

        $this->em->flush();

        return $request;
    }
}
