<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 03.06.2016 16:30
 */

namespace skobka\appDi\interfaces;

/**
 * Interface ApplicationInterface
 * @package skobka\common\interfaces
 */
interface ApplicationInterface
{
    /**
     * Start an application instance
     * @return void
     */
    public function start();
}
