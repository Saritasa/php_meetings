<?php

namespace App\Model\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * App\Model\Entities\Word
 *
 * @property int $id
 * @property string $word
 * @property bool $synonyms_saved
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Collection|Word[] $synonyms
 * @property-read Collection|SynonymLink[] $synonymLinks
 * @method static Builder|Word whereId($value)
 * @method static Builder|Word whereWord($value)
 * @method static Builder|Word whereCreatedAt($value)
 * @method static Builder|Word whereUpdatedAt($value)
 * @method static Builder|Word whereSynonymsSaved($value)
 * @mixin \Eloquent
 */
class Word extends Model
{
    protected $table = 'words';

    protected $fillable = ['word'];

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'word_id' => 'integer',
        'synonym_id' => 'integer'
    ];

    public function synonyms() : BelongsToMany
    {
        return $this->belongsToMany(Word::class, 'synonyms', 'word_id', 'synonym_id');
    }

    public function synonymLinks() : HasMany
    {
        return $this->hasMany(SynonymLink::class);
    }
}
