<?php

namespace Mechawrench\Laracoins\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'laracoins_transactions';

    public $guarded = [];

    public static function logTransaction($to, $from, $quantity, $comment = null)
    {
       return Transactions::create([
            'user_id' => $to,
            'to_user_id' => $from,
            'quantity' => $quantity,
            'comment' => $comment,
        ]);
    }
}
