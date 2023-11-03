<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MapList;

class MapListBatch extends Model
{
    use HasFactory;

    protected $table = "map_list_batch";
    protected $fillable = ['id', 'map_id', 'rank', 'player_count'];

    protected $guarded = [];

    /**
     * Get the user that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function map_list() {
        return $this->belongsTo(MapList::class);
    }

}
