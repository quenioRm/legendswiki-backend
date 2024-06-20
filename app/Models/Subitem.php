<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subitem extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'topic_id'];

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}


