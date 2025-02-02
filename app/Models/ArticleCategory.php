<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ArticleCategory extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_article_category');
    }
}
