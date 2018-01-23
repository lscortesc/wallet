<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Transaction
 * @package App
 */
class Transaction extends Model
{
    /**
     * @var string
     */
    protected $table = 'transactions';
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'id', 'wallet_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function commission()
    {
        return $this->hasOne(Commission::class, 'transaction_id', 'id');
    }
}
