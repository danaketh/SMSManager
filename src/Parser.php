<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use SimPod\SmsManager\Exception\XmlParsingFailed;
use Throwable;

use function libxml_use_internal_errors;

use const LIBXML_NONET;

class Parser
{
    /** @param array<string,string|int|bool> $config */
    public static function parseXmlResponseBody(ResponseInterface|null $response, array $config = []): SimpleXMLElement
    {
        $internalErrors = libxml_use_internal_errors(true);
        try {
            // Allow XML to be retrieved even if there is no response body
            $xml = new SimpleXMLElement(
                $response === null ? '<root />' : (string) $response->getBody(),
                isset($config['libxml_options']) ? (int) $config['libxml_options'] : LIBXML_NONET,
                false,
                isset($config['ns']) ? (string) $config['ns'] : '',
                isset($config['ns_is_prefix']) && (bool) $config['ns_is_prefix'],
            );
            libxml_use_internal_errors($internalErrors);
        } catch (Throwable $exception) {
            libxml_use_internal_errors($internalErrors);

            throw new XmlParsingFailed(
                'Unable to parse response body into XML: ' . $exception->getMessage(),
                0,
                $exception,
            );
        }

        return $xml;
    }
}
