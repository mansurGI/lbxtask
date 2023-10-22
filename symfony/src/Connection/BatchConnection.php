<?php

namespace App\Connection;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;

class BatchConnection extends \Doctrine\DBAL\Connection
{
    /**
     * Inserts (if value is unique) a table row (or rows) with specified data.
     *
     * @param string $table Table name
     * @param array<string, mixed> $data [column => value] or [[column => value], ...] for multiple rows insert
     * @param array<int|string, int|string|Type|null> $types Parameter types
     *
     * @return int|string The number of affected rows
     * @throws Exception
     */
    public function insertIgnore(string $table, array $data, array $types = []): int|string
    {
        if (count($data) === 0) {
            return $this->executeStatement('INSERT INTO ' . $table . ' () VALUES ()');
        }

        // multiple rows or one
        if (is_array($data[0])) {
            $columns = implode(', ', array_keys($data[0]));
            $values = call_user_func_array('array_merge', $data);
            $set = '(' . implode(', ', array_fill(0, count($data[0]), '?')) . ')';
            $set = implode(', ', array_fill(0, count($data), $set));
        } else {
            $columns = implode(', ', array_keys($data));
            $values = array_values($data);
            $set = '(' . implode(', ', array_fill_keys(array_keys($data), '?')) . ')';
        }

        return $this->executeStatement(
            sprintf('INSERT IGNORE INTO %s (%s) VALUES %s', $table, $columns, $set),
            $values,
            $types,
        );
    }
}