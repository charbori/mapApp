<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Follow extends Model
{
    use HasFactory;

    protected $table = "follow";

    /**
     * Get the user that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sports_record() {
        return $this->hasMany(SportsRecord::class);
    }
}
