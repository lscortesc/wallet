<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Wallet
 * @package App
 */
class Wallet extends Model
{
    /**
     * @var string
     */
    protected $table = 'wallets';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Customer::class, 'id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'wallet_id', 'id');
    }
}
