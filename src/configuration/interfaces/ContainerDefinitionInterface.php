<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 03.06.2016 23:22
 */

namespace skobka\appDi\configuration\interfaces;

/**
 * Interface ContainerDefinitionInterface
 * @package skobka\common\configuration\interfaces
 */
interface ContainerDefinitionInterface
{
    /**
     * Returns definitions for \DI\ContainerBuilder
     * @return array
     */
    public function getDefinitions();
}
