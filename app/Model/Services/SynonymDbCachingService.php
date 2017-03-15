<?php

namespace App\Model\Services;

use App\Model\Entities\SynonymLink;
use App\Model\Entities\Word;
use Log;

/**
 * Proxy for Synonym Service
 * Stores synonyms in local DB to avoid multiple 3d party service requests
 */
class SynonymDbCachingService implements ISynonymService
{
    /** @var ISynonymService */
    private $synonymService;

    function __construct(ISynonymService $synonymService)
    {
        $this->synonymService = $synonymService;
    }

    function getSynonyms(string $word): array
    {
        /* @var Word $w */
        $w = Word::with('synonyms')->firstOrNew(['word' => $word]);
        if (!$w->exists) {
            $w->save();
        }
        if (!$w->synonyms->isEmpty() || $w->synonyms_saved) {
            return $w->synonyms->pluck('word')->all();
        }

        $synonyms = $this->synonymService->getSynonyms($word);

        Log::info('Found '.count($synonyms)." synonyms for word '$word', saving: ".implode(', ', $synonyms));

        $this->saveSynonyms($w, $synonyms);

        return $synonyms;
    }

    static function saveSynonyms(Word $word, array $synonyms)
    {
        foreach ($synonyms as $synonym) {
            $s = Word::firstOrNew(['word' => $synonym]);
            if (!$s->exists) {
                $s->save();
            }
            $link = SynonymLink::firstOrNew(['word_id' => $word->id, 'synonym_id' => $s->id]);
            if (!$link->exists) {
                $link->save();
            }
        }
        $word->synonyms_saved = true;
        $word->save();
    }
}
