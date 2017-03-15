<?php

namespace TestUnit;

use App\Model\Services\SynonymService;
use Tests\TestCase;

class SynonymServiceTest extends TestCase
{
    function testGetSynonyms()
    {
        $ss = app(SynonymService::class);
        $synonyms = $ss->getSynonyms('advocate');
        $this->assertContains('lawyer', $synonyms);
    }
}
