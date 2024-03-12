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

    public function menuUserNavbar(): array
    {
        $menu = parent::menuUserNavbar();
        $items = data_get($menu, "items", []);
        $user = Auth::user();
        $menuItems = [
            "Profile" => route('supernova.modules.details', ['module' => 'users', 'id' => $user->id]),
        ];
        if ($user->role === "root") {
            $menuItems["Configurações"] = route('supernova.modules.index', ['module' => 'settings']);
        }
        $menu["items"] = [...$menuItems, ...$items];
        return $menu;
    }
}
