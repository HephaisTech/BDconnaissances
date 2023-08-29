<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'departement',
        'project',
        'content',
        'author_id',
    ];

    protected $casts = [
        'comment_count' => 'integer', // Cast the comment_count attribute to an integer
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags', 'article_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentCount()
    {
        return $this->hasMany(Comment::class)
            ->selectRaw('article_id, count(*) as count')
            ->groupBy('article_id');
    }
}