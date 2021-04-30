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
        $available = Coin::firstOrCreate(['user_id' => $from_user]);
        $send_to = Coin::firstOrCreate(['user_id' => $to_user_id]);

        // Ensure account is not locked
        if ($available->is_locked || $send_to->is_locked) {
            return 'Failed, account coins are locked';
        }

        // Ensure enough quantity is available for trade
        if ($from_user != 0 && $available->quantity < $quantity) {
            return 'Failed, available balance is below quantity';
        }

        // Grab from bank if not from user, bank has infinite supply...
        if ($from_user !== 0) {
            $available->quantity = $available->quantity - $quantity;
            $available->save();
        }

        // Refresh user, prevents duping coins when sending to self
        $send_to->refresh();

        $send_to->quantity = $send_to->quantity + $quantity;
        $send_to->save();

        return 0;
    }

    public static function lock($user_id)
    {
        $user = Coin::firstOrCreate(['user_id' => $user_id]);

        if ($user->is_locked) {
            return 'User is already locked';
        }

        $user->is_locked = true;
        $user->save();

        return $user;
    }

    public static function unlock($user_id)
    {
        $user = Coin::firstOrCreate(['user_id' => $user_id]);

        if (! $user->is_locked) {
            return 'User is already unlocked';
        }

        $user->is_locked = false;
        $user->save();

        return $user;
    }

    public function user()
    {
        if (class_exists('App\User')) {
            return $this->belongsTo('App\User');
        }

        if (class_exists('App\Models\User')) {
            return $this->belongsTo('App\Models\User');
        }

        return null;
    }
}
