<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleNews extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "name",
        "thumbnail",
        "content",
        "category_id",
        "is_featured",
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryArticle::class, 'category_id');
    }
}
