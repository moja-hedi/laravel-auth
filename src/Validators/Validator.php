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

namespace MojaHedi\Auth\Validators;

use MojaHedi\Auth\Contracts\Validator as ValidatorContract;
use MojaHedi\Auth\Exceptions\JWTException;
use MojaHedi\Auth\Support\RefreshFlow;

abstract class Validator implements ValidatorContract
{
    use RefreshFlow;

    /**
     * Helper function to return a boolean.
     *
     * @param array $value
     *
     * @return bool
     */
    public function isValid($value)
    {
        try {
            $this->check($value);
        } catch (JWTException $e) {
            return false;
        }

        return true;
    }

    /**
     * Run the validation.
     *
     * @param array $value
     *
     * @return void
     */
    abstract public function check($value);
}
