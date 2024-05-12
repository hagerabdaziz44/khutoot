<?php
namespace App\Http\Traits;



Trait AdminGuardTrait
{

        function getGuard ()
    {
        return auth()->guard('admin');
    }

}

