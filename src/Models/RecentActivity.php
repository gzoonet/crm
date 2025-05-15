<?php

namespace Gzoonet\Crm\Models;

use Illuminate\Database\Eloquent\Model;

class RecentActivity extends Model
{
    public $timestamps = false;

    protected $table = 'recent_activity'; // Not a real table, just placeholder for fromSub()

    protected $guarded = [];
}
