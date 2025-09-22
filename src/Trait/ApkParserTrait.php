<?php

declare(strict_types=1);

namespace KetPHP\ApkParser\Trait;

use KetPHP\ApkParser\Exception\ManifestException;
use KetPHP\ApkParser\Parser\Application;
use KetPHP\ApkParser\Parser\Compatibility;
use KetPHP\ApkParser\Parser\Package;

trait ApkParserTrait
{

    /**
     * @throws ManifestException
     */
    public function getPackage(): Package
    {
        if (preg_match("/package:\s*(.*)/", $this->manifest, $matches)) {
            return new Package($matches[1]);
        }
        throw new ManifestException("Failed to extract package information from manifest.");
    }

    /**
     * @throws ManifestException
     */
    public function getCompatibility(): Compatibility
    {
        if (preg_match_all("/^\s*(sdkVersion:'[^']+'|targetSdkVersion:'[^']+')\s*$/m", $this->manifest, $matches)) {
            $sdkLines = implode("\r\n", $matches[1]) . "\r\n";
            return new Compatibility($sdkLines);
        }
        throw new ManifestException("Failed to extract compatibility information from manifest.");
    }

    /**
     * @throws ManifestException
     */
    public function getApplication(): Application
    {
        if (preg_match("/application:\s*(.*)/", $this->manifest, $matches)) {
            return new Application($matches[1]);
        }
        throw new ManifestException("Failed to extract application information from manifest.");
    }

    public function getPermissions(): array
    {
        $permissions = [];

        if (preg_match_all("/^uses-permission(?:-sdk-(\d+))?: name='([^']+)'/m", $this->manifest, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $permissions[$match[2]] = [
                    "sdk" => $match[1] ?? null,
                ];
            }
        }

        return $permissions;
    }

    public function getLabels(): array
    {
        $labels = [];

        if (preg_match_all("/^application-label(?:-([\w+-]+))?:'([^']+)'/m", $this->manifest, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $locale = $match[1] ?? "";
                $labels[$locale] = $match[2];
            }
        }

        return $labels;
    }

    /**
     * @throws ManifestException
     */
    public function getLocales(): array
    {
        $locales = [];

        if (preg_match("/^locales:\s+(.+)$/m", $this->manifest, $match)) {
            if (preg_match_all("/'([^']+)'/", $match[1], $matches)) {
                $locales = $matches[1];
            }
            return $locales;
        }
        throw new ManifestException("Failed to extract locales information from manifest.");
    }
}