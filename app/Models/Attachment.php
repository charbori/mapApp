<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Attachment extends Model
{
    use HasFactory;

    protected $table = "attachment";
    protected $fillable = ['articles_id', 'path'];

    /**
     * Get the user that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article() {
        return $this->belongsTo(Article::class);
    }
}
