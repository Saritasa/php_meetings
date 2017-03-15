<?php

namespace App\Model\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;

/**
 * App\Model\Entities\Synonym
 *
 * @property int $id
 * @property int $word_id
 * @property int $synonym_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Model\Entities\Word $synonymWord
 * @property-read \App\Model\Entities\Word $word
 * @method static Builder|SynonymLink whereId($value)
 * @method static Builder|SynonymLink whereWordId($value)
 * @method static Builder|SynonymLink whereSynonymId($value)
 * @method static Builder|SynonymLink whereCreatedAt($value)
 * @method static Builder|SynonymLink whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SynonymLink extends Model
{
    protected $table = 'synonyms';

    protected $fillable = ['word_id', 'synonym_id'];

    protected $dates = ['created_at', 'updated_at'];

    public function word(): HasOne
    {
        return $this->hasOne(Word::class);
    }

    public function synonymWord(): HasOne
    {
        return $this->hasOne(Word::class, 'synonym_id');
    }
}
