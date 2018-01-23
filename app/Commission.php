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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'id', 'transaction_id');
    }
}
