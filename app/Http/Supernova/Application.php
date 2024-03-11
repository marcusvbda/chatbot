<?php

namespace App\Http\Supernova;

use marcusvbda\supernova\Application as SupernovaApplication;
use Auth;

class Application extends SupernovaApplication
{
    public function darkMode(): bool
    {
        return Auth::check() && Auth::user()->dark_mode ? true : false;
    }
}
