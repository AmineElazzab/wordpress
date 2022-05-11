<?php

namespace YaySMTP\Aws3\Aws\Api;

/**
 * Base class representing a modeled shape.
 */
class Shape extends \YaySMTP\Aws3\Aws\Api\AbstractModel
{
    /**
     * Get a concrete shape for the given definition.
     *
     * @param array    $definition
     * @param ShapeMap $shapeMap
     *
     * @return mixed
     * @throws \RuntimeException if the type is invalid
     */
    public static function create(array $definition, \YaySMTP\Aws3\Aws\Api\ShapeMap $shapeMap)
    {
        static $map = ['structure' => 'YaySMTP\\Aws3\\Aws\\Api\\StructureShape', 'map' => 'YaySMTP\\Aws3\\Aws\\Api\\MapShape', 'list' => 'YaySMTP\\Aws3\\Aws\\Api\\ListShape', 'timestamp' => 'YaySMTP\\Aws3\\Aws\\Api\\TimestampShape', 'integer' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'double' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'float' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'long' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'string' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'byte' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'character' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'blob' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape', 'boolean' => 'YaySMTP\\Aws3\\Aws\\Api\\Shape'];
        if (isset($definition['shape'])) {
            return $shapeMap->resolve($definition);
        }
        if (!isset($map[$definition['type']])) {
            throw new \RuntimeException('Invalid type: ' . print_r($definition, true));
        }
        $type = $map[$definition['type']];
        return new $type($definition, $shapeMap);
    }
    /**
     * Get the type of the shape
     *
     * @return string
     */
    public function getType()
    {
        return $this->definition['type'];
    }
    /**
     * Get the name of the shape
     *
     * @return string
     */
    public function getName()
    {
        return $this->definition['name'];
    }
}
