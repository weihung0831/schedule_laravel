<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedules';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'machine_name',
        'changed_schedule_qty',
        'changed_manual_work_hours',
        'schedule_date',
    ];

    protected $casts = [
        'changed_schedule_qty' => 'integer',
        'changed_manual_work_hours' => 'integer',
    ];
}
