<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Model for banned domain
 * @property int $id
 * @property string $domain
 */
class BannedDomain  extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain'
    ];
}
