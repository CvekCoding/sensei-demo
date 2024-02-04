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
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class EqualConditionMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if ($message instanceof ExecuteRequest) {
            $message->setToExecute(preg_replace('/([^=])=([^=])/', '${1}===${2}', $message->command));
        }

        return $stack->next()->handle($envelope, $stack);
    }
}
