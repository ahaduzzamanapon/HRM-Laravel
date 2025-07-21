<?php
Route::get('auto-login', function () {
    Auth::loginUsingId(1, $remember = true);

    //redirect to home page now
    return Redirect::to('/');
});
