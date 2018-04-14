<?php

namespace SocialiteProviders\StaffConnect;

use SocialiteProviders\Manager\SocialiteWasCalled;
use Illuminate\Http\Request;

class StaffConnectExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('staffconnect', __NAMESPACE__.'\Provider');
    }
}
