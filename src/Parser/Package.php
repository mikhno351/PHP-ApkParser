<?php

declare(strict_types=1);

namespace KetPHP\ApkParser\Parser;

use KetPHP\ApkParser\Exception\ManifestExtractException;
use KetPHP\ApkParser\Interface\ParserInterface;

final class Package implements ParserInterface
{

    public function __construct(protected readonly string $package)
    {
    }

    public function __toString(): string
    {
        return $this->package;
    }

    /**
     * @throws ManifestExtractException
     */
    public function getName(): string
    {
        if (preg_match("/name='([^']+)'/", $this->package, $matches)) {
            return $matches[1];
        }
        throw new ManifestExtractException("Failed to extract package name from manifest line.");
    }

    /**
     * @throws ManifestExtractException
     */
    public function getVersionCode(): int
    {
        if (preg_match("/versionCode='([^']+)'/", $this->package, $matches)) {
            return (int)$matches[1];
        }
        throw new ManifestExtractException("Failed to extract version code from manifest line.");
    }

    /**
     * @throws ManifestExtractException
     */
    public function getVersionName(): string
    {
        if (preg_match("/versionName='([^']+)'/", $this->package, $matches)) {
            return $matches[1];
        }
        throw new ManifestExtractException("Failed to extract version name from manifest line.");
    }

    /**
     * @throws ManifestExtractException
     */
    public function getCompileSdkVersion(): AndroidPlatform
    {
        if (preg_match("/compileSdkVersion='([^']+)'/", $this->package, $matches)) {
            return new AndroidPlatform((int)$matches[1]);
        }
        throw new ManifestExtractException("Failed to extract compile sdk version from manifest line.");
    }

    /**
     * @throws ManifestExtractException
     */
    public function toArray(): array
    {
        return [
            "name" => $this->getName(),
            "version" => [
                "name" => $this->getVersionName(),
                "code" => $this->getVersionCode(),
            ],
            "compileSdkVersion" => $this->getCompileSdkVersion()->toArray()
        ];
    }
}