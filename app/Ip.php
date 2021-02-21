<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    protected $table = 'ip';
    protected $primaryKey = 'id';
    protected $fillable = ['ip', 'disabled'];
}
