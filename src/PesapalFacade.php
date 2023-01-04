<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/5/23, 1:03 AM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

namespace Patricmutwiri\Pesapal;

use Illuminate\Support\Facades\Facade;

class PesapalFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'pesapal';
    }
}
