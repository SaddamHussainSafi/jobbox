<?php

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * Source http://stackoverflow.com/a/13733588/179104
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Botble\Paystack\Services;

class TransRef
{
    /**
     * Get the pool to use based on the type of prefix hash
     * @param  string $type
     * @return string
     */
    private static function getPool($type = 'alnum')
    {
        return match ($type) {
            'alnum' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'alpha' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            'hexdec' => '0123456789abcdef',
            'numeric' => '0123456789',
            'nozero' => '123456789',
            'distinct' => '2345679ACDEFHJKLMNPRSTUVWXYZ',
            default => (string) $type,
        };
    }

    /**
     * Generate a random secure crypt figure
     * @param  integer $min
     * @param  integer $max
     * @return integer
     */
    private static function secureCrypt($min, $max)
    {
        $range = $max - $min;

        if ($range < 0) {
            return $min; // not so random...
        }

        $log    = log($range, 2);
        $bytes  = (int) ($log / 8) + 1; // length in bytes
        $bits   = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);

        return $min + $rnd;
    }

    /**
     * Finally, generate a hashed token
     * @param  integer $length
     * @return string
     */
    public static function getHashedToken($length = 25)
    {
        $token = '';
        $max   = strlen(static::getPool());
        for ($i = 0; $i < $length; $i++) {
            $token .= static::getPool()[static::secureCrypt(0, $max)];
        }

        return $token;
    }
}
