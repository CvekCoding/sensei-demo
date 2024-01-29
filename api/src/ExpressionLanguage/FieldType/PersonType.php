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

namespace App\ExpressionLanguage\FieldType;

final readonly class PersonType implements FieldType
{
    public function getName(): string
    {
        return 'Person';
    }
}
