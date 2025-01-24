<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'packing_date',
        'plan_begin_date',
        'plan_end_date',
        'product_line_code',
        'customer_code',
        'order_number',
        'order_spec',
        'product_number',
        'product_name',
        'product_spec',
        'plan_qty',
        'actual_qty',
        'schedule_qty',
        'manual_work_hours',
        'erp_work_hours',
        'stock',
        'manufacturing_number',
        'is_scheduled',
        'is_completed',
    ];

    protected $casts = [
        'plan_qty' => 'integer',
        'actual_qty' => 'integer',
        'schedule_qty' => 'integer',
        'manual_work_hours' => 'integer',
        'erp_work_hours' => 'integer',
        'stock' => 'integer',
        'is_scheduled' => 'boolean',
        'is_completed' => 'boolean',
    ];
}
