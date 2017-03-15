<?php

namespace App\Model\Services;

interface ISynonymService
{
    function getSynonyms(string $word): array;
}
