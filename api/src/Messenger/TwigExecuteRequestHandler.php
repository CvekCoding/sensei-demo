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
use App\Entity\TwigExecuteRequest;
use App\ExpressionLanguage\SenseiExpressionProvider;
use Doctrine\ORM\EntityManagerInterface;
use Okvpn\Expression\TwigLanguage;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Twig\Error\SyntaxError;
use Twig\TwigFunction;

#[AsMessageHandler]
final readonly class TwigExecuteRequestHandler
{
    private TwigLanguage $expressionLanguage;

    public function __construct(
        private EntityManagerInterface $em,
        SenseiExpressionProvider $senseiExpressionProvider,
    ) {
        $this->expressionLanguage = new TwigLanguage();

        foreach ($senseiExpressionProvider->getFunctions() as $function) {
            $this->expressionLanguage->addFunction(
                new TwigFunction(
                    name: $function->getName(),
                    callable: $function->getEvaluator(),
                    options: ['needs_context' => true]
                )
            );
        }
   }

    public function __invoke(TwigExecuteRequest $request): TwigExecuteRequest
    {
        try {
            $this->expressionLanguage->validateExpression(expression: $request->getToExecute());
        } catch (SyntaxError $e) {
            throw new ValidationException(message: $e->getMessage(), previous: $e);
        }

        $result = $this->expressionLanguage->evaluate(
            $request->getToExecute(),
            array_merge(
                ['request' => $request],
                ['greeting' => $request->context?->greeting],
                ['person' => $request->context?->person],
                ['globalValue' => ['var1' => $request->context?->globalValue]],
            )
        ) ?? null;

        $request->setResult($result);

        $this->em->flush();

        return $request;
    }
}
