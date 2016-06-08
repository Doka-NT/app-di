<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 10.05.2016 9:42
 */

namespace skobka\appDi\database\exceptions;

class NotUniqueResultException extends \Exception
{
    public $message = 'NotUniqueResultException';
}
