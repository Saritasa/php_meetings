<?php


namespace App\Model\Services;


use Doctrine\Instantiator\Exception\InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Log;

/**
 * Synonym service
 * Gets synonyms for words from 3d-party API
 * 
 * Http client for http://words.bighugelabs.com/api.php
 */
class SynonymService implements ISynonymService
{
    const BASE_URL = 'http://words.bighugelabs.com/api/2';

    function getSynonyms(string $word): array
    {
        try {
            $data = $this->getApiResponse($word);
        }
        catch (RequestException $e)
        {
            Log::error("Could not get synonyms for '$word': ".$e->getMessage());
            return [];
        }
        $result = [];
        if (isset($data['noun']) && isset($data['noun']['syn'])) {
            $result = array_merge($result, $data['noun']['syn']);
        }
        if (isset($data['verb']) && isset($data['verb']['syn'])) {
            $result = array_merge($result, $data['verb']['syn']);
        }
        return $result;
    }

    private function getApiResponse(string $word): array
    {
        $key = config('services.thesaurus.api_key');
        if (!$key) {
            throw new InvalidArgumentException('Thesaurus API key not set in configuration');
        }
        $format = 'php'; // Get response as serialized PHP array
        $url = self::BASE_URL."/$key/$word/$format";
        $httpClient = new Client();
        $response = $httpClient->get($url);
        $serializedArray = $response->getBody(true);
        return unserialize($serializedArray);
    }
}