<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MapAttachment;

class MapList extends Model
{
    use HasFactory;

    protected $table = "map_list";
    protected $fillable = ['user_id', 'title', 'type', 'desc', 'longitude', 'latitude', 'attachment', 'rank', 'address', 'tag', 'player_count'];

    protected $guarded = [];

    /**
     * Get the user that owns the Article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function attachment()
    {
        return $this->hasMany(MapAttachment::class, 'map_id');
    }

    public function sports_record()
    {
        return $this->hasMany(SportsRecord::class, 'map_id');
    }
}
