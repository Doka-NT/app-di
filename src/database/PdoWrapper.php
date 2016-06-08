<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 10.05.2016 9:29
 */

namespace skobka\appDi\database;

use DI\FactoryInterface;
use skobka\appDi\database\exceptions\NoResultException;
use skobka\appDi\database\exceptions\NotUniqueResultException;

class PdoWrapper implements DatabaseInterface
{
    /**
     * @var string
     */
    protected $tablePrefix = '';

    /**
     * @var string
     */
    protected $tablePostfix = '';
    /**
     * @var string
     */
    protected $prefixPlaceholder = '{{';
    /**
     * @var string
     */
    protected $postfixPlaceholder = '}}';

    /**
     * @var FactoryInterface
     */
    protected $container;

    /**
     * PdoWrapper constructor.
     * @param FactoryInterface $container
     */
    public function __construct(FactoryInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->tablePrefix;
    }

    /**
     * @param string $tablePrefix
     * @return $this
     */
    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getTablePostfix()
    {
        return $this->tablePostfix;
    }

    /**
     * @param string $tablePostfix
     * @return $this
     */
    public function setTablePostfix($tablePostfix)
    {
        $this->tablePostfix = $tablePostfix;
        return $this;
    }

    /**
     * Execute prepared SQL statement and returns one row
     * @param string $statement
     * @param array $params
     * @param string $class
     * @return \stdClass Instance of $class
     * @throws NoResultException
     * @throws NotUniqueResultException
     */
    public function fetchOne($statement, $params = [], $class = \stdClass::class)
    {
        $result = $this->fetchAll($statement, $params, $class);
        $count = count($result);
        if (!$count) {
            throw new NoResultException;
        } elseif ($count > 1) {
            throw new NotUniqueResultException;
        }
        return $result[0];
    }

    /**
     * Execute prepared SQL statement
     * @param string $statement
     * @param array $params
     * @param $class
     * @return array
     */
    public function fetchAll($statement, $params = [], $class = \stdClass::class)
    {
        $st = $this->getPreparedStatement($statement);
        $st->setFetchMode(\PDO::FETCH_OBJ);
        $st->execute($params);
        return $st->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    /**
     * @param $statement
     * @return \PDOStatement
     */
    protected function getPreparedStatement($statement)
    {
        $statement = $this->setPrefixAndPostfix($statement);
        $st = $this->getConnection()->prepare($statement);
        return $st;
    }

    /**
     * @param string $statement
     * @return string
     */
    protected function setPrefixAndPostfix($statement)
    {
        return str_replace([
            $this->prefixPlaceholder,
            $this->postfixPlaceholder,
        ], [
            $this->tablePrefix,
            $this->tablePostfix,
        ], $statement);
    }

    /**
     * @return \PDO
     * @throws \DI\NotFoundException
     */
    protected function getConnection()
    {
        /* @var $dbh \PDO */
        $dbh = $this->container->make(\PDO::class);
        $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $dbh->exec('set names utf8');
        return $dbh;
    }

    /**
     * @param string $statement
     * @param array $params
     * @return bool
     */
    public function raw($statement, $params = [])
    {
        $st = $this->getPreparedStatement($statement);
        return $st->execute($params);
    }

    /**
     * @param string $table
     * @param array $data
     * @return bool
     */
    public function insert($table, array $data)
    {
        $fields = [];
        $ins = [];
        $values = [];
        foreach ($data as $field => $v) {
            $ph = ':' . $field;
            $ins[] = $ph;
            $fields[] = $field;
            $values[$ph] = $v;
        }

        $ins = implode(',', $ins);
        $fields = implode(',', $fields);
        $sql = "INSERT INTO " . $this->setPrefixAndPostfix($table) . " ($fields) VALUES ($ins)";

        $connection = $this->getConnection();
        $st = $connection->prepare($sql);
        $st->execute($values);
        return $connection->lastInsertId();
    }

    /**
     * @param \PDO $connection
     * @return string
     */
    public function lastInsertId(\PDO $connection)
    {
        return $connection->lastInsertId();
    }
}
