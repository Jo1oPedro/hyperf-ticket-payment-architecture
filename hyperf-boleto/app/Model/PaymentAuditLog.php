<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id 
 * @property string $payment_id 
 * @property string $event 
 * @property string $before_status 
 * @property string $after_status 
 * @property string $actor 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class PaymentAuditLog extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'payment_audit_log';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        "payment_id",
        "event",
        "before_status",
        "after_status",
        "actor",
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['created_at' => 'datetime', 'updated_at' => 'datetime'];
}
