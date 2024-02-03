<?php

declare(strict_types=1);

namespace Axleus\Storage;

use Laminas\Hydrator\ReflectionHydrator;
use Axleus\Db;

use function method_exists;

class AbstractRepository implements Db\RepositoryInterface, Db\RepositoryCommandInterface
{
    use RepositoryTrait;

    public function __construct(
        private Db\TableGateway $gateway,
        private ReflectionHydrator $hydrator = new ReflectionHydrator(),
    ) {
    }

    public function __call($name, $arguments)
    {
        if (method_exists($this->gateway, $name)) {
            return $this->gateway->$name(...$arguments);
        }
    }
}
