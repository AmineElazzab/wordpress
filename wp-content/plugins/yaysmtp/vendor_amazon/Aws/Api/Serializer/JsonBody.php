<?php

namespace YaySMTP\Aws3\Aws\Api\Serializer;

use YaySMTP\Aws3\Aws\Api\Service;
use YaySMTP\Aws3\Aws\Api\Shape;
use YaySMTP\Aws3\Aws\Api\TimestampShape;
/**
 * Formats the JSON body of a JSON-REST or JSON-RPC operation.
 * @internal
 */
class JsonBody
{
    private $api;
    public function __construct(\YaySMTP\Aws3\Aws\Api\Service $api)
    {
        $this->api = $api;
    }
    /**
     * Gets the JSON Content-Type header for a service API
     *
     * @param Service $service
     *
     * @return string
     */
    public static function getContentType(\YaySMTP\Aws3\Aws\Api\Service $service)
    {
        return 'application/x-amz-json-' . number_format($service->getMetadata('jsonVersion'), 1);
    }
    /**
     * Builds the JSON body based on an array of arguments.
     *
     * @param Shape $shape Operation being constructed
     * @param array $args  Associative array of arguments
     *
     * @return string
     */
    public function build(\YaySMTP\Aws3\Aws\Api\Shape $shape, array $args)
    {
        $result = json_encode($this->format($shape, $args));
        return $result == '[]' ? '{}' : $result;
    }
    private function format(\YaySMTP\Aws3\Aws\Api\Shape $shape, $value)
    {
        switch ($shape['type']) {
            case 'structure':
                $data = [];
                foreach ($value as $k => $v) {
                    if ($v !== null && $shape->hasMember($k)) {
                        $valueShape = $shape->getMember($k);
                        $data[$valueShape['locationName'] ?: $k] = $this->format($valueShape, $v);
                    }
                }
                return $data;
            case 'list':
                $items = $shape->getMember();
                foreach ($value as $k => $v) {
                    $value[$k] = $this->format($items, $v);
                }
                return $value;
            case 'map':
                if (empty($value)) {
                    return new \stdClass();
                }
                $values = $shape->getValue();
                foreach ($value as $k => $v) {
                    $value[$k] = $this->format($values, $v);
                }
                return $value;
            case 'blob':
                return base64_encode($value);
            case 'timestamp':
                return \YaySMTP\Aws3\Aws\Api\TimestampShape::format($value, 'unixTimestamp');
            default:
                return $value;
        }
    }
}
