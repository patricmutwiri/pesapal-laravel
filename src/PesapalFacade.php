<?php
/*
 * Copyright (c) 2023.
 * @author Patrick Mutwiri on 1/4/23, 11:44 PM
 * @twitter https://twitter.com/patricmutwiri
 *
 */

namespace Patricmutwiri\Pesapal;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Patricmutwiri\Pesapal\Skeleton\SkeletonClass
 */
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
