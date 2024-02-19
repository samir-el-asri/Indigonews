<?php

namespace App\Search;

use Algolia\ScoutExtended\Searchable\Aggregator;
use App\Article;
use App\Profile;
use App\User;

class All_index extends Aggregator
{
    /**
     * The names of the models that should be aggregated.
     *
     * @var string[]
     */
    protected $models = [
        \App\Article::class,
        \App\Profile::class,
        \App\User::class
    ];
}
