<?php

namespace YaySMTP\Aws3\Aws\Api\Parser;

use YaySMTP\Aws3\Aws\Api\Service;
use YaySMTP\Aws3\Aws\Api\StructureShape;
use YaySMTP\Aws3\Psr\Http\Message\ResponseInterface;

/**
 * @internal Implements REST-XML parsing (e.g., S3, CloudFront, etc...)
 */
class RestXmlParser extends \YaySMTP\Aws3\Aws\Api\Parser\AbstractRestParser {
  use PayloadParserTrait;
  /** @var XmlParser */
  private $parser;
  /**
   * @param Service   $api    Service description
   * @param XmlParser $parser XML body parser
   */
  public function __construct(\YaySMTP\Aws3\Aws\Api\Service $api, \YaySMTP\Aws3\Aws\Api\Parser\XmlParser $parser = null) {
    parent::__construct($api);
    $this->parser = $parser ?: new \YaySMTP\Aws3\Aws\Api\Parser\XmlParser();
  }
  protected function payload(\YaySMTP\Aws3\Psr\Http\Message\ResponseInterface $response, \YaySMTP\Aws3\Aws\Api\StructureShape $member, array &$result) {
    $xml = $this->parseXml($response->getBody());
    $result += $this->parser->parse($member, $xml);
  }
}
