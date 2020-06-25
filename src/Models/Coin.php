<?php

namespace Mechawrench\Laracoins\Models;

use Illuminate\Database\Eloquent\Model;

class Coin extends Model
{
    protected $table = 'laracoins_coins';

    public $guarded = [];

    public static function trade($from_user, $to_user_id, $quantity, $comment = null)
    {
        // Check for available balance
        $available = Coin::where('user_id', $from_user)->first();
        $send_to = Coin::where('user_id', $to_user_id)->first();

        // Ensure account is not locked
        if ($available->is_locked || $send_to->is_locked) {
            return 'Failed, account coins are locked';
        }

        // Ensure enough quantity is available for trade
        if ($available->quantity < $quantity) {
            return 'Failed, available balance is below quantity';
        }

        $available->quantity = $available->quantity - $quantity;
        $available->save();

        $send_to->quantity = $send_to->quantity + $quantity;
        $send_to->save();

        Transactions::logTransaction($to_user_id, $from_user, $quantity, $comment);

        return 0;
    }

    public static function lock($user_id)
    {
        $user = Coin::where('user_id', $user_id)->first();

        if (! $user) {
            return 'User not found';
        }

        if ($user->is_locked) {
            return 'User is already locked';
        }

        $user->is_locked = true;
        $user->save();

        return $user;
    }

    public static function unlock($user_id)
    {
        $user = Coin::where('user_id', $user_id)->first();

        if (! $user) {
            return 'User not found';
        }

        if (!$user->is_locked) {
            return 'User is already unlocked';
        }

        $user->is_locked = false;
        $user->save();

        return $user;
    }
}
