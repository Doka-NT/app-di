<?php
/**
 * @author Soshnikov Artem <213036@skobka.com>
 * @copyright (c) 03.06.2016 23:01
 */

namespace skobka\appDi\configuration;

use skobka\appDi\configuration\exceptions\ConfigurationBuildException;
use skobka\appDi\configuration\interfaces\ConfigurationInterface;

/**
 * Class Builder
 * @package uroweb\common\configuration
 */
class Builder
{
    /**
     * @param \stdClass $rawConf
     * @return \skobka\appDi\configuration\interfaces\ConfigurationInterface
     * @throws \skobka\appDi\configuration\exceptions\ConfigurationBuildException
     */
    public static function build(\stdClass $rawConf)
    {
        return static::nestedBuild($rawConf);
    }

    /**
     * @param \stdClass $nodeDefinition
     * @return \skobka\appDi\configuration\interfaces\ConfigurationInterface
     * @throws \skobka\appDi\configuration\exceptions\ConfigurationBuildException
     */
    protected static function nestedBuild(\stdClass $nodeDefinition)
    {
        $class = property_exists(
            $nodeDefinition,
            'class'
        ) ? new $nodeDefinition->class : \stdClass::class;

        $nodeObject = new $class();

        $isStdClass = get_class($nodeObject) == \stdClass::class;
        if (!$isStdClass && (!$nodeObject instanceof ConfigurationInterface)) {
            throw new ConfigurationBuildException(
                "Configuration node must implements ".ConfigurationInterface::class
            );
        }

        foreach ($nodeDefinition as $property => $value) {
            if (!property_exists($nodeObject, $property)
                && $property !== 'class'
            ) {
                throw new ConfigurationBuildException(
                    'Unknown property '.$property.' in class '
                    .get_class($nodeObject)
                );
            }
            $nodeObject->$property = is_object($value) ? static::nestedBuild(
                $value
            ) : $value;
        }

        return $nodeObject;
    }
}
