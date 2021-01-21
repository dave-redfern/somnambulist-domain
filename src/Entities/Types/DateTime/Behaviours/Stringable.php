<?php declare(strict_types=1);

namespace Somnambulist\Components\Domain\Entities\Types\DateTime\Behaviours;

use DateTime as PhpDateTime;
use Somnambulist\Components\Domain\Entities\Types\DateTime\DateTime;

/**
 * Trait Stringable
 *
 * Based on Carbon\Carbon test setup methods.
 *
 * @package    Somnambulist\Components\Domain\Entities\Types\DateTime\Behaviours
 * @subpackage Somnambulist\Components\Domain\Entities\Types\DateTime\Behaviours\Stringable
 */
trait Stringable
{

    private string $defaultFormat = 'Y-m-d H:i:s';

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->format($this->defaultFormat);
    }

    public function setDefaultFormat(string $format): DateTime
    {
        $this->defaultFormat = $format;

        return $this;
    }

    public function toDateString(): string
    {
        return $this->format('Y-m-d');
    }

    public function toFormattedDateString(): string
    {
        return $this->format('M j, Y');
    }

    public function toTimeString(): string
    {
        return $this->format('H:i:s');
    }

    public function toDateTimeString(): string
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function toDayDateTimeString(): string
    {
        return $this->format('D, M j, Y g:i A');
    }

    public function toAtomString(): string
    {
        return $this->format(PhpDateTime::ATOM);
    }

    public function toCookieString(): string
    {
        return $this->format(PhpDateTime::COOKIE);
    }

    public function toIso8601String(): string
    {
        return $this->toAtomString();
    }

    public function toRfc822String(): string
    {
        return $this->format(PhpDateTime::RFC822);
    }

    public function toRfc2822String(): string
    {
        return $this->format(PhpDateTime::RFC2822);
    }

    public function toRssString(): string
    {
        return $this->format(PhpDateTime::RSS);
    }

    public function toW3cString(): string
    {
        return $this->format(PhpDateTime::W3C);
    }
}
