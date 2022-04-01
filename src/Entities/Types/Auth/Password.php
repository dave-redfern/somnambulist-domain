<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Entities\Types\Auth;

use Assert\Assert;
use Somnambulist\Components\Domain\Entities\AbstractValueObject;
use function password_get_info;
use function strtolower;

/**
 * Class Password
 *
 * @package    Somnambulist\Components\Domain\Entities\Types\Auth
 * @subpackage Somnambulist\Components\Domain\Entities\Types\Auth\Password
 */
class Password extends AbstractValueObject
{
    public function __construct(private string $value)
    {
        Assert::that($value, null, 'password')->notEmpty()->notBlank()->maxLength(255)->satisfy(function ($string) {
            $info = password_get_info($string);

            return 0 !== $info['algo'] && 'unknown' !== strtolower($info['algoName']);
        }, 'The password string must be pre-hashed');
    }

    public function value(): string
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
