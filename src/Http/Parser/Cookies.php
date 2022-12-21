<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) 2014-2021 Sean armj <armj148@gmail.com>
 * (c) 2021 PHP Open Source Saver
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MojaHedi\Auth\Http\Parser;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use MojaHedi\Auth\Contracts\Http\Parser as ParserContract;
use MojaHedi\Auth\Exceptions\TokenInvalidException;

class Cookies implements ParserContract
{
    use KeyTrait;

    /**
     * Decrypt or not the cookie while parsing.
     *
     * @var bool
     */
    private $decrypt;

    public function __construct($decrypt = true)
    {
        $this->decrypt = $decrypt;
    }

    /**
     * Try to parse the token from the request cookies.
     *
     * @return string|null
     *
     * @throws TokenInvalidException
     */
    public function parse(Request $request)
    {
        if ($this->decrypt && $request->hasCookie($this->key)) {
            try {
                return Crypt::decrypt($request->cookie($this->key));
            } catch (DecryptException $ex) {
                throw new TokenInvalidException('Token has not decrypted successfully.');
            }
        }

        return $request->cookie($this->key);
    }
}
