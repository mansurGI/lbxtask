<?php

namespace App\Connection;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;

class BatchConnection extends \Doctrine\DBAL\Connection
{
    /**
     * Inserts (if not unique) a table row with specified data.
     *
     * @param string $table Table name
     * @param array<string, mixed> $data Column-value pairs ['name' => 'Steve']
     * @param array<int|string, int|string|Type|null> $types Parameter types
     *
     * @return int|string The number of affected rows
     * @throws Exception
     */
    public function insertIgnore(string $table, array $data, array $types = []): int|string
    {
        return $this->executeStatement(
            'INSERT IGNORE INTO ' . $table . ' (' . implode(', ', array_keys($data)) . ')' .
            ' VALUES (' . implode(', ', array_fill_keys(array_keys($data), '?')) . ')',
            array_values($data),
            $types,
        );
    }
}