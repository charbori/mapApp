<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MapList;

class MapAttachment extends Model
{
    use HasFactory;

    protected $table = "map_attachemnt";
    protected $fillable = ['map_id', 'path'];

    /**
     * Get the user that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mapList() {
        return $this->belongsTo(MapList::class);
    }
}

