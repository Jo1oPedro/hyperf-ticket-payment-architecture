<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id 
 * @property string $payment_id 
 * @property string $outcome 
 * @property string $payload_hash 
 * @property string $recieved_at 
 * @property string $processes_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class WebhookDelivery extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'webhook_deliveries';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        "payment_id",
        "outcome",
        "payload_hash",
        "received_at",
        "processes_at",
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'received_at' => 'datetime',
        'processes_at' => 'datetime',
    ];
}
