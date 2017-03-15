<?php

use App\Model\Entities\Word;
use App\Model\Services\SynonymDbCachingService;
use Illuminate\Database\Seeder;

class SynonymSeeder extends Seeder
{
    const WORDS = ['advocate' => ['attorney', 'lawyer', 'defender']];

    public function run()
    {
        foreach (static::WORDS as $word => $synonyms) {
            $w = $this->findOrCreate($word);
            SynonymDbCachingService::saveSynonyms($w, $synonyms);
        }
    }

    public function findOrCreate(string $word): Word
    {
        if (Word::whereWord($word)->exists()) {
            return Word::whereWord($word)->first();
        } else {
            return Word::create(['word' => $word]);
        }
    }
}