<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'body',
        'rating'
    ];

    /*
     * Get the user for the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
