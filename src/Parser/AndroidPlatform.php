<?php

declare(strict_types=1);

namespace KetPHP\ApkParser\Parser;

use DateTimeImmutable;
use Exception;
use KetPHP\ApkParser\Interface\ParserInterface;
use Okapi\Path\Path;

final class AndroidPlatform implements ParserInterface
{

    private readonly array $android;

    public function __construct(protected readonly int $version)
    {
        $all = (array)(parse_ini_file(Path::join(dirname(__DIR__, 2), "bin", "android.ini"), true, INI_SCANNER_TYPED) ?: []);
        $this->android = $all[$this->version] ?? [];
    }

    public function __toString(): string
    {
        return implode("; ", $this->android);
    }

    public function getCode(): int
    {
        return $this->version;
    }

    public function getName(): string
    {
        return sprintf("Android %s", $this->getVersion());
    }

    public function getVersion(): string
    {
        return $this->get("version", "NaN");
    }

    public function getCodeName(): ?string
    {
        return $this->get("codename");
    }

    public function getShortName(): ?string
    {
        return $this->get("shortname");
    }

    /**
     * @throws Exception
     */
    public function getRelease(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->get("release", "now"));
    }

    protected function get(string $name, mixed $default = null): mixed
    {
        return $this->android[$name] ?? $default;
    }

    public function toArray(): array
    {
        return $this->android;
    }
}