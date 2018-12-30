<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['item_id', 'user_id', 'object_id', 'previous_state', 'current_state', 'signature', 'identification_code'];
}
