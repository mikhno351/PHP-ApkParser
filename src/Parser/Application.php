<?php

declare(strict_types=1);

namespace KetPHP\ApkParser\Parser;

use KetPHP\ApkParser\Exception\ManifestExtractException;
use KetPHP\ApkParser\Interface\ParserInterface;

final class Application implements ParserInterface
{

    public function __construct(protected readonly string $application)
    {
    }

    public function __toString(): string
    {
        return $this->application;
    }

    /**
     * @throws ManifestExtractException
     */
    public function getLabel(): string
    {
        if (preg_match("/label='([^']+)'/", $this->application, $matches)) {
            return $matches[1];
        }
        throw new ManifestExtractException("Failed to extract application label from manifest line.");
    }

    /**
     * @throws ManifestExtractException
     */
    public function getIcon(): string
    {
        if (preg_match("/icon='([^']+)'/", $this->application, $matches)) {
            return $matches[1];
        }
        throw new ManifestExtractException("Failed to extract application icon from manifest line.");
    }

    /**
     * @throws ManifestExtractException
     */
    public function toArray(): array
    {
        return [
            'label' => $this->getLabel(),
            'icon' => $this->getIcon()
        ];
    }
}