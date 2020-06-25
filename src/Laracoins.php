<?php

namespace Mechawrench\Laracoins;

use Mechawrench\Laracoins\Models\Coin;

class Laracoins
{
    public static function tradeCoins($from, $to, $quantity, $comment)
    {
        return Coin::trade($from, $to, $quantity, $comment);
    }

    public static function lockUser($user_id)
    {
        return Coin::lock($user_id);
    }

    public static function unlockUser($user_id)
    {
        return Coin::unlock($user_id);
    }

    // TODO: Coins from system, user_id of 0 (fundUser)
}
