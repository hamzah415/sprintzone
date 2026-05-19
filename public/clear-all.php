Route::get('/clear-all', function() {
    \Artisan::call('route:clear');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    return "Cache cleared! Coba akses login google lagi.";
});