<?php

use Illuminate\Support\Facades\Route;

Route::post('hook.json', 'Darkzn\Ghubautopull\GhubAutopullController@handlehook');
