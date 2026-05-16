<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property string $id 
 * @property string $status 
 * @property string $barcode 
 * @property int $amount_cents 
 * @property string $currency 
 * @property string $payer_document 
 * @property string $bank_authorization_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Payment extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'payments';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = ['amount_cents' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
