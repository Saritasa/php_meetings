<?php

namespace App\Model\Services;

use Cache;

/**
 * Caching proxy for Synonym service
 * Stores synonym values in fast cache to reduce number of DB queries
 */
class SynonymCachingService implements ISynonymService
{
    const CACHE_PERIOD = 60;

    /**
     * @var ISynonymService
     */
    private $synonymService;

    function __construct(ISynonymService $synonymService)
    {
        $this->synonymService = $synonymService;
    }

    public function getSynonyms(string $word): array
    {
        $key = static::getKey($word);
        $synonyms = Cache::get($key);
        if ($synonyms) {
            return $synonyms;
        }

        $synonyms = $this->synonymService->getSynonyms($word);
        Cache::put($key, $synonyms, static::CACHE_PERIOD);
        return $synonyms;
    }

    public static function getKey(string $word)
    {
        return md5("synonym:$word");
    }
}
