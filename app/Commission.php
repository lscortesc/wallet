<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Commission
 * @package App
 */
class Commission extends Model
{
    /**
     * @var string
     */
    protected $table = 'commissions';

    /**
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
        'percentage' => 'float',
        'fixed_rate' => 'float'
    ];
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
