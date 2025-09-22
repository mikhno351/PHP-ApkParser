<?php

declare(strict_types=1);

namespace KetPHP\ApkParser;

use KetPHP\ApkParser\Exception\ManifestException;
use KetPHP\ApkParser\Exception\ManifestExtractException;
use KetPHP\ApkParser\Interface\ParserInterface;
use KetPHP\ApkParser\Trait\ApkParserTrait;
use Okapi\Path\Path;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ApkParser implements ParserInterface
{

    protected readonly string $aaptPath;

    protected readonly string $manifest;

    public function __construct(string $filename)
    {
        $this->aaptPath = Path::join(dirname(__DIR__), "bin", "aapt", $this->detectOS(), "aapt");

        $process = new Process([$this->aaptPath, "dump", "badging", $filename]);
        $process->run();

        if ($process->isSuccessful() === false) {
            throw new ProcessFailedException($process);
        }

        $this->manifest = $process->getOutput();
    }

    public function __toString(): string
    {
        return $this->manifest;
    }

    private function detectOS(): string
    {
        return match (substr(strtolower(PHP_OS_FAMILY), 0, 3)) {
            "win" => "windows",
            "dar", "mac" => "macos",
            default => "linux",
        };
    }

    use ApkParserTrait;

    /**
     * @throws ManifestException
     * @throws ManifestExtractException
     */
    public function toArray(): array
    {
        return [
            "package" => $this->getPackage()->toArray(),
            "compatibility" => $this->getCompatibility()->toArray(),
            "application" => $this->getApplication()->toArray(),
            "permissions" => $this->getPermissions(),
            "labels" => $this->getLabels(),
            "locales" => $this->getLocales(),
            "nativeCodes" => $this->getNativeCodes(),
        ];
    }
}