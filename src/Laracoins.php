<?php

namespace Mechawrench\Laracoins;

use Mechawrench\Laracoins\Models\Coin;
use Mechawrench\Laracoins\Models\Transactions;

class Laracoins
{
    public static function tradeCoins($from, $to, $quantity, $comment)
    {
        $trade = Coin::trade($from, $to, $quantity, $comment);

        if ($trade !== 0) {
            return $trade;
        }

        return Transactions::logTransaction($from, $to, $quantity, $comment);
    }

    public static function lockUser($user_id)
    {
        return Coin::lock($user_id);
    }

    public static function unlockUser($user_id)
    {
        return Coin::unlock($user_id);
    }

    public static function userHistory($user_id)
    {
        $history = Transactions::whereUserId($user_id)
            ->orWhere('to_user_id', $user_id)
            ->orderBy('created_at', 'desc');

        if (class_exists('App\User')) {
            $history->with('user');
        }

        if (class_exists('App\Models\User')) {
            $history->with('user');
        }

        return $history->get();
    }

    public static function fundUser($user_id, $quantity, $comment)
    {
        Coin::trade(0, $user_id, $quantity, $comment);

        return Transactions::logTransaction(0, $user_id, $quantity, $comment);
    }

    public static function balance($user_id)
    {
        return Coin::firstOrCreate(['user_id' => $user_id])->quantity;
    }

    public static function topHolders($quantity = 10)
    {
        $holders = Coin::where('quantity', '>', 0)
            ->orderBy('quantity', 'desc');

        if (class_exists('App\User')) {
            $holders->with('user');
        }

        if (class_exists('App\Models\User')) {
            $holders->with('user');
        }

        return $holders->get()->take($quantity);
    }
}
