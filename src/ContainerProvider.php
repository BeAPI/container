<?php

namespace Beapi\Container;

final class ContainerProvider
{
    private static $instance;

    /**
     * Get a container instance.
     *
     * @return Container
     */
    public static function getContainer(): Container
    {
        if (null === self::$instance) {
            self::$instance = new Container();
        }

        return self::$instance;
    }
}
