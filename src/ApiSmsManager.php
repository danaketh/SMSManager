<?php

declare(strict_types=1);

namespace SimPod\SmsManager;

use Assert\Assertion;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use SimpleXMLElement;
use SimPod\SmsManager\Exception\SendingFailed;

use function assert;
use function dom_import_simplexml;

final class ApiSmsManager implements SmsManager
{
    private const XML_BASE_PATH = 'https://xml-api.smsmanager.cz/';
    private const XML_PATH_SEND = 'Send';

    private string $apiKey;

    private Client $xmlClient;

    public function __construct()
    {
        $this->xmlClient = new Client(
            [
                'base_uri' => self::XML_BASE_PATH,
            ],
        );
    }

    public function setApiKey(string $apiKey): void
    {
        $this->apiKey = $apiKey;
    }

    public function send(SmsMessage $smsMessage): Response|bool
    {
        $xml = $this->buildXml($smsMessage);

        if ($xml === null) {
            return false;
        }

        try {
            $response = $this->xmlClient->post(
                self::XML_PATH_SEND,
                [
                    'multipart' => [
                        [
                            'name'     => 'XMLDATA',
                            'contents' => $xml,
                        ],
                    ],
                ],
            );

            return $this->buildResponseData($response);
        } catch (ClientException | ServerException $exception) {
            $response = Parser::parseXmlResponseBody($exception->getResponse());

            throw SendingFailed::forRecipients($smsMessage->getRecipients(), $response);
        }
    }

    private function buildXml(SmsMessage $smsMessage): string|null
    {
        $xml           = new SimpleXMLElement('<RequestDocument/>');
        $requestHeader = $xml->addChild('RequestHeader');
        $requestHeader->addChild('Apikey', $this->apiKey);
        $request = $xml
            ->addChild('RequestList')
            ->addChild('Request');

        $request->addAttribute('Type', $smsMessage->getRequestType()->getValue());

        if ($smsMessage->getSender() !== null) {
            $request->addAttribute('Sender', $smsMessage->getSender());
        }

        if ($smsMessage->getCustomId() !== null) {
            $request->addAttribute('CustomID', (string) $smsMessage->getCustomId());
        }

        $request->addChild('Message', $smsMessage->getMessage())->addAttribute('Type', 'Text');

        $numberList = $request->addChild('NumbersList');

        $hasAnyNumber = false;
        foreach ($smsMessage->getRecipients() as $recipient) {
            Assertion::e164($recipient);
            $numberList->addChild('Number', $recipient);
            $hasAnyNumber = true;
        }

        /* removes <?xml version="1.0"?> */
        $dom = dom_import_simplexml($xml);
        assert($dom->ownerDocument !== null);
        $xml = $dom->ownerDocument->saveXML($dom->ownerDocument->documentElement);
        assert($xml !== false);

        return $hasAnyNumber ? $xml : null;
    }

    private function buildResponseData(ResponseInterface $apiResponse): Response
    {
        $result = new SimpleXMLElement((string) $apiResponse->getBody());

        $responseId   = (int) $result->Response['ID']; // @phpcs:ignore
        $responseType = (string) $result->Response['Type']; // @phpcs:ignore

        $response = new Response($responseId, $responseType);

        $responseRequestList = $result->ResponseRequestList; // @phpcs:ignore
        assert($responseRequestList instanceof SimpleXMLElement);

        foreach ($responseRequestList->ResponseRequest as $request) { // @phpcs:ignore
            $responseRequest = new ResponseRequest(
                (int) $request->RequestID, // @phpcs:ignore
                (int) $request->CustomID, // @phpcs:ignore
                (int) $request['SmsCount'],
                (float) $request['SmsPrice'],
            );

            $responseNumbersList = $request->ResponseNumbersList; // @phpcs:ignore
            assert($responseNumbersList instanceof SimpleXMLElement);
            foreach ($responseNumbersList->Number as $phoneNumber) { // @phpcs:ignore
                $responseRequest->addNumber((string) $phoneNumber);
            }

            $response->addResponseRequest($responseRequest);
        }

        return $response;
    }
}
