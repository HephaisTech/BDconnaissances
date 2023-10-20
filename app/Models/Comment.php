<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['article_id', 'author_id', 'content', 'withfile', 'upvotes'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function upvoters()
    {
        return $this->belongsToMany(User::class, 'comment_upvotes')->withTimestamps();
    }
}
