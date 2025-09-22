<?php

declare(strict_types=1);

namespace KetPHP\ApkParser\Parser;

use KetPHP\ApkParser\Exception\ManifestException;
use KetPHP\ApkParser\Interface\ParserInterface;

final class Compatibility implements ParserInterface
{

    public function __construct(protected readonly string $compatibility)
    {
    }

    public function __toString(): string
    {
        return $this->compatibility;
    }

    /**
     * @throws ManifestException
     */
    public function getMinSdkVersion(): AndroidPlatform
    {
        if (preg_match("/sdkVersion:'([^']+)'/", $this->compatibility, $matches)) {
            return new AndroidPlatform((int)$matches[1]);
        }
        throw new ManifestException("Failed to extract min sdk version information from manifest.");
    }

    /**
     * @throws ManifestException
     */
    public function getTargetSdkVersion(): AndroidPlatform
    {
        if (preg_match("/targetSdkVersion:'([^']+)'/", $this->compatibility, $matches)) {
            return new AndroidPlatform((int)$matches[1]);
        }
        throw new ManifestException("Failed to extract target sdk version information from manifest.");
    }

    /**
     * @throws ManifestException
     */
    public function toArray(): array
    {
        return [
            'min' => $this->getMinSdkVersion()->toArray(),
            'target' => $this->getTargetSdkVersion()->toArray(),
        ];
    }
}