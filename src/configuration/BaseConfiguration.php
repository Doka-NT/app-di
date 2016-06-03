<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 03.06.2016 16:53
 */

namespace skobka\appDi\configuration;

use skobka\appDi\configuration\interfaces\ConfigurationInterface;
use skobka\appDi\configuration\interfaces\ContainerDefinitionInterface;

/**
 * Class BaseDefinition
 * @package uroweb\common\abstraction\configuration
 */
class BaseConfiguration implements ConfigurationInterface, ContainerDefinitionInterface
{

    /**
     * @var BasePdoConfiguration
     */
    public $pdo;

    /**
     * @var array @see README.md for mor information
     */
    public $containers;

    /**
     * Returns definitions for \DI\ContainerBuilder
     * @return array
     */
    public function getDefinitions()
    {
        return $this->containers;
    }
}
