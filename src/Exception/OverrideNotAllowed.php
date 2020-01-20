<?php

namespace Beapi\Container\Exception;

use Psr\Container\ContainerExceptionInterface;

class OverrideNotAllowed extends \RuntimeException implements ContainerExceptionInterface
{

    public static function forIdentifier(string $name): OverrideNotAllowed
    {
        return new self("Container already has value with the id {$name}.");
    }
}
