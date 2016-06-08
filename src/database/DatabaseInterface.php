<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 10.05.2016 9:29
 */

namespace skobka\appDi\database;

interface DatabaseInterface
{
    /**
     * Execute prepared SQL statement
     * @param string $statement
     * @param array $params
     * @return mixed
     */
    public function fetchAll($statement, $params = []);
}
