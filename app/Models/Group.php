<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\MapAttachment;

class Group extends Model
{
    use HasFactory;

    protected $table = "groups";
    protected $fillable = ['name', 'owner'];

    protected $guarded = [];

    /**
     * Get the user that owns the UserGroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users_group() {
        return $this->hasMany(UserGroup::class, 'group_id');
    }

    /**
     * Get the user that owns the SportsRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sports_record()
    {
        return $this->hasMany(SportsRecord::class, 'group_id');
    }
}
