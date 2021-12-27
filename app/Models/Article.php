<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ["title", "content"];
    protected $with = ["tags"];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
