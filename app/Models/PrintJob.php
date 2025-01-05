<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJob extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id'];

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_SUCCESS = 'success';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_UNPAID = 'unpaid';

    const PAYMENT_ENETS = 'enets';

    const JOB_STATUS_IN_PROGRESS = 'in_progress';
    const JOB_STATUS_DONE = 'done';

    protected $casts = [
        'selected_option_items' => 'array',
    ];
}
