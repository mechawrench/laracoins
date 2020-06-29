<?php

namespace Mechawrench\Laracoins\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $table = 'laracoins_transactions';

    public $guarded = [];

    public static function logTransaction($from, $to, $quantity, $comment = null)
    {
        return Transactions::create([
            'user_id' => $from,
            'to_user_id' => $to,
            'quantity' => $quantity,
            'comment' => $comment,
        ]);
    }

    public function user()
    {
       return $this->belongsTo('App\User');
    }
}
