<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 03.06.2016 16:55
 */

namespace skobka\appDi\configuration;

use skobka\appDi\configuration\interfaces\ConfigurationInterface;

/**
 * Class BasePdoConfiguration
 * @package skobka\common\abstraction\configuration
 */
class BasePdoConfiguration implements ConfigurationInterface
{
    /**
     * @var string DSN string like mysql:dbname=testdb;host=127.0.0.1
     */
    public $dsn;
    /**
     * @var string
     */
    public $user;
    /**
     * @var string
     */
    public $password;
}
