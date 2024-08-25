<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Banner\Banner;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function show()
    {
        $banners = Banner::all();

        return Inertia::render('Settings/Settings', [
            'banners' => $banners,
        ]);
    }
}
