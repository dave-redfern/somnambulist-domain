<?php declare(strict_types=1);

namespace Somnambulist\Domain\Entities\Types\Identity;

use Assert\Assert;
use Somnambulist\Domain\Entities\AbstractValueObject;

/**
 * Class EmailAddress
 *
 * @package    Somnambulist\Domain\Entities\Types\Identity
 * @subpackage Somnambulist\Domain\Entities\Types\Identity\EmailAddress
 */
final class EmailAddress extends AbstractValueObject
{

    private string $value;

    public function __construct(string $email)
    {
        Assert::that($email, null, 'email')->notEmpty()->email()->maxLength(100);

        $this->value = $email;
    }

    public function toString(): string
    {
        return (string)$this->value;
    }

    public function account(): string
    {
        return mb_substr($this->value, 0, mb_strpos($this->value, '@'));
    }

    public function domain(): string
    {
        return mb_substr($this->value, mb_strpos($this->value, '@') + 1);
    }
}
