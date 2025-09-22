<?php

declare(strict_types=1);

namespace KetPHP\ApkParser\Interface;

interface ParserInterface
{

    public function __toString(): string;

    public function toArray(): array;
}