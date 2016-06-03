<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 03.06.2016 16:31
 */

namespace skobka\appDi\interfaces;

use Interop\Container\ContainerInterface;

/**
 * Class ContainerisedInterface
 * @package uroweb\common\interfaces
 */
interface ContainerisedInterface
{
    /**
     * Set container to the current object
     * @param \Interop\Container\ContainerInterface $container
     * @return void
     */
    public function setContainer(ContainerInterface $container);
}
