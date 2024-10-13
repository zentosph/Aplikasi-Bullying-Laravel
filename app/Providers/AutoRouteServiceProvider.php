<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use ReflectionClass;

class AutoRouteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function registerAutoRoutes()
{
    $controllerNamespace = 'App\\Http\\Controllers\\';
    $controllersPath = app_path('Http/Controllers');

    foreach (scandir($controllersPath) as $file) {
        if (Str::endsWith($file, 'Controller.php')) {
            $controllerClass = $controllerNamespace . pathinfo($file, PATHINFO_FILENAME);

            $reflection = new ReflectionClass($controllerClass);
            foreach ($reflection->getMethods() as $method) {
                if ($method->isPublic() && !$method->isConstructor()) {
                    $methodName = $method->getName();

                    // Build route URL based on the controller and method name
                    $routeUrl = Str::kebab(str_replace('Controller', '', $reflection->getShortName())) . '/' . Str::kebab($methodName);

                    // Tambahkan segmen dinamis hanya jika nama metode TIDAK dimulai dengan 'post' atau 'get'
                    if ($method->getNumberOfParameters() > 0 && !Str::startsWith($methodName, ['post', 'get'])) {
                        $routeUrl .= '/{id}'; // Menggunakan {id} sebagai parameter dinamis
                    }

                    // Check if the method is meant to handle a POST request
                    if (Str::startsWith($methodName, 'post')) {
                        Route::post($routeUrl, [$controllerClass, $methodName])->middleware('web');
                    } else {
                        // Default to GET for other methods
                        Route::get($routeUrl, [$controllerClass, $methodName])->middleware('web');
                    }
                }
            }
        }
    }
}



    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerAutoRoutes();
    }
}
