<?php

namespace Beapi\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;

class IdentifierNotFound extends \RuntimeException implements NotFoundExceptionInterface
{

    public static function forIdentifier(string $name): IdentifierNotFound
    {
        return new self("Container doesn't contain a service or value for the id {$name}");
    }
}
