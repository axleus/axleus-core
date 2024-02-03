<?php

declare(strict_types=1);

namespace Axleus\Storage;

use Laminas\Db\ResultSet\ResultSetInterface;
use Axleus\Db\EntityInterface;
use Axleus\Db\ModelTrait;
use InvalidArgumentException;

trait RepositoryTrait
{
    use ModelTrait;

    /**
     *
     * @param EntityInterface|array $entity
     * @return EntityInterface|int
     * @throws InvalidArgumentException
     */
    public function save(EntityInterface|array $entity): EntityInterface|int
    {
        if ($entity instanceof EntityInterface) {
            $set = $this->hydrator->extract($entity);
        }
        if ($set === []) {
            throw new \InvalidArgumentException('Repository can not save empty entity.');
        }
        try {
            if (! isset($set['id']) ) {
                // insert
                $this->gateway->insert($set);
                $set['id'] = $this->gateway->getLastInsertValue();
            } else {
                $this->gateway->update($set, ['id' => $set['id']]);
            }
        } catch (\Throwable $th) {
            // will be caught by the commandbus
        }
        if (is_array($entity)) {
            $entity = $this->gateway->getResultSetPrototype();
        }
        return $this->hydrator->hydrate($set, $entity);
    }

    public function findOneById(int $id): EntityInterface { }

    public function findOneByColumn(string $column, int|string $value): ResultSetInterface|EntityInterface { }

    public function findManyByColumn(array $titles): ResultSetInterface { }

    public function fetchAll(): ResultSetInterface { }

    public function delete(EntityInterface $entity): int { }
}
