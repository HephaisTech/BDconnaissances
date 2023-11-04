<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $table = 'steps';
    protected $fillable = ['article_id', 'description', 'order', 'attached_file'];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
