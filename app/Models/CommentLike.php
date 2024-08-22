<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'comment_id',
        'user_id',  // Add any other attributes you need to mass assign
    ];
    use HasFactory;
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
