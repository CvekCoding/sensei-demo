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

use App\ExpressionLanguage\FieldType\InputField;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag(self::TAG)]
interface ExpressionFunctionInterface
{
    public const TAG = 'app.expression_function';

    public function getName();

    /** @return InputField[] */
    public function getInputFields(): iterable;

    public function getResult(): string;

    public function compiler(): \Closure;

    public function evaluator(): \Closure;
}
