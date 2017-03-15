<?php

namespace Tests\Unit;

use App\Exceptions\NotImplementedException;
use App\Model\Entities\SynonymLink;
use App\Model\Entities\Word;
use App\Model\Services\ISynonymService;
use App\Model\Services\SynonymDbCachingService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SynonymDbCachingServiceTest extends TestCase
{
    use DatabaseMigrations;

    function testSynonymCaching()
    {
        // Ensure, that synonyms are not cached;
        $advocate = Word::with('synonymLinks')->whereWord('advocate')->first();
        if ($advocate) {
            $advocate->synonymLinks->each(function (SynonymLink $synonymLink) {
                $synonymLink->delete();
            });
        }

        $scs = new SynonymDbCachingService(new class implements ISynonymService {
            function getSynonyms(string $word): array
            {
                return ['attorney', 'lawyer', 'defender'];
            }
        });
        $synonyms = $scs->getSynonyms('advocate');
        $this->assertEquals(3, count($synonyms), "stub must return 3 synonyms");
        $this->assertContains('lawyer', $synonyms, "stub response must contain 'lawyer' word");

        // Ensure, those synonyms were saved in DB for following use
        $advocate = Word::with('synonyms')->whereWord('advocate')->first();
        $this->assertNotNull($advocate);
        $this->assertNotNull($advocate->synonyms);
        $this->assertEquals(3, $advocate->synonyms->count());
        $this->assertContains('lawyer', $advocate->synonyms->pluck('word'));
    }

    function testSavedSynonymsReturnedWithoutApiCall()
    {
        // SynonymSeeder creates 'advocate' and 3 synonyms, including 'lawyer'
        (new \SynonymSeeder())->run();

        // Ensure, that 'advocate' and synonyms are saved in DB
        $advocate = Word::with('synonyms')->whereWord('advocate')->first();
        $this->assertNotNull($advocate);
        $this->assertFalse($advocate->synonyms->isEmpty());

        // Create caching service with API service stub, that will throw exception, if service makes call to it
        $scs = new SynonymDbCachingService(new class implements ISynonymService {
            function getSynonyms(string $word): array
            {
                throw new NotImplementedException('Underlying synonym Service must not be called');
            }
        });
        
        $synonyms = $scs->getSynonyms('advocate');
        $this->assertContains('lawyer', $synonyms);
    }
}
