<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/7/23, 3:18 PM
 * @twitter https://twitter.com/patric_mutwiri
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
