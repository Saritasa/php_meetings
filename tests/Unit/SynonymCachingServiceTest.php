<?php

namespace Tests\Unit;

use App\Exceptions\NotImplementedException;
use App\Model\Entities\Word;
use App\Model\Services\ISynonymService;
use App\Model\Services\SynonymCachingService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Proxy for Synonym Service
 * Stores synonyms in local DB to avoid multiple 3d party service requests
 */
class SynonymCachingServiceTest extends TestCase
{
    function testSynonymCaching()
    {
        // Ensure, that synonyms are not cached;
        Cache::forget(SynonymCachingService::getKey('advocate'));

        $scs = new SynonymCachingService(new class implements ISynonymService {
            function getSynonyms(string $word): array
            {
                return ['attorney', 'lawyer', 'defender'];
            }
        });
        $synonyms = $scs->getSynonyms('advocate');
        $this->assertEquals(3, count($synonyms), "stub must return 3 synonyms");
        $this->assertContains('lawyer', $synonyms, "stub response must contain 'lawyer' word");
    }

    function testCachedSynonymsReturnedWithoutApiCall()
    {
        // Ensure, that 'advocate' and synonyms are in cache
        Cache::put(SynonymCachingService::getKey('advocate'), ['attorney', 'lawyer', 'defender'], 10);

        // Create caching service with API service stub, that will throw exception, if service makes call to it
        $scs = new SynonymCachingService(new class implements ISynonymService {
            function getSynonyms(string $word): array
            {
                throw new NotImplementedException('Underlying synonym Service must not be called');
            }
        });
        
        $synonyms = $scs->getSynonyms('advocate');
        $this->assertContains('lawyer', $synonyms);
    }
}
