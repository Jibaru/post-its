<?php

namespace App\Models;

use Database\Factories\GroupFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return GroupFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'description',
        'created_by_id'
    ];

    /**
     * The users on the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(User::class, 'users_groups', 'group_id', 'user_id');
    }

    /**
     * The user that created the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    /**
     * The postits of the group
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postits()
    {
        return $this->hasMany(Postit::class, 'group_id', 'id');
    }
}
