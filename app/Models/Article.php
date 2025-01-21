<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'slug',
        'content',
        'status',
        'image',
        'published_at',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function generateSlug()
    {
        $slug = Str::slug($this->title);
        if (static::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . Str::random(5);
        }
        $this->slug = $slug;
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($article) {
            if ($article->image && file_exists(storage_path('app/public/' . $article->image))) {
                unlink(storage_path('app/public/' . $article->image));
            }
        });
    }
}
