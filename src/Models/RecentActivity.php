<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    public $timestamps = false;

    protected $table = 'recent_activity';

    protected $guarded = [];
}
