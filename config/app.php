<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application. This value is used when the
    | framework needs to place the application's name in a notification or
    | any other location as required by the application or its packages.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL', null),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    |
    */

    'timezone' => 'Asia/Shanghai',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    |
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    |
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Faker Locale
    |--------------------------------------------------------------------------
    |
    | This locale will be used by the Faker PHP library when generating fake
    | data for your database seeds. For example, this will be used to get
    | localized telephone numbers, street address information and more.
    |
    */

    'faker_locale' => 'en_US',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    |
    */

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Package Service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Mews\Captcha\CaptchaServiceProvider::class,
        Chumper\Zipper\ZipperServiceProvider::class,
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [

        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Captcha' => Mews\Captcha\Facades\Captcha::class,
        'Zipper' => Chumper\Zipper\Zipper::class,
        'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
    ],
    'ALI_CONFIG'=>[
        'APP_ID' => '2021001192645285', //appId
        'encryptKey'=>'WyoL+MVVB4yHKUYBFeb1Aw==',
        'RSA_PRIVATE_KEY'=>'MIIEogIBAAKCAQEAjW2fT6Fhggoxaw98PgWxA0UI4t01Y2u/tNntUcuz0J2s+2k51i54vQbZoOPcJWLYcfH89h98k2zEgNge0Tt2DDlEchWqi7SOBvSnLT8kaaGIh93s03DFlVNngmaA0hrvpvEtVp5ycEox8dZl3IK1CB6g27+TlrTKwIWATCeW17aKxpyLJ6J501T1kp+xqmM3/Emn4oH4QANmYC7Vai2c09CEahdN5MezF+p6XwhkIoo07veIFt6ulnVw4ZgPHmhvZX1J7bugl7yBa0PQOpl9FjAMYdTaC4f6GO0bfTVHJwlhCU5So01KV5DJgw3C0TMOFdhME48gqioXtWRy2UOvJQIDAQABAoIBABAzwtCImHchW8/8Eiu40zKgsgfrd7ZQHcJGRR3dzFfV+H9E6s9Su5pMMppwAER4Mnu7UVOQ/+CT0V9BYyZtPXThCqpXORaQNMUvOgfA+Bbx3oZSMlN20+vrhiw2OgPX+iSR9LO7qziaB2bBPSIqBztK5vrF086sH542rSAMWQeVQiSw+F8BrQdmgXJQ8k2mJjHDvQwPlNAi7mC1YS482eb0jMFBfVD222/uyv3L24+fQTxm+1JwU0iOQBQs2lN1aowwAG0KLm1PYcJAuHqWXncP/62dbeazwkl5vYE9aZXogKg5F3RHYqlSPGVP4i7WnZuLDOeXmkKTywPXNY/H/gECgYEA1atwSUwqvVM1Avza+7fFkJeZNjdfcRXjE5wvQOA4LqumAY3p3InYGr2HQqPET5UFxxs8oWObjRYZuhSO/KQb9bf/N7X+tDmg1NMzI7vTEbWODWtwha7upXXuHRv6qVVYGWka7aMUPgDk1HwPO0VCZKEgzmSBuU2nah+lbTvni0UCgYEAqXJa3ZzuqlL/DJtqQ7Pcq5/Qzvbx8bFuhjI1QKxlQ3lenwfhqOr0oLbIUVoycKHhJLwgp+nUU0C/zRX4ns9p3d47fjOOtL0b16MHK37zZDyK2lsCJZOBs6gtiWTP3WYlzc6DVC2t9/4gGd4RgS4T+J+oEFjWgz8d+DObg2YR4mECgYAOoQE8E3ntom/Dt5oql8dwAeEchCgFrxDv+8aEc5WFJH1mJ9g2ID3qfsVu+5VqOXK+0g+RoSc6PqQeSGANzhKX/TP9FXnNzxBC4f7jWG24Da0fIBcBIBv4uh1GYu8DfvNcMgUE9iBhqmAAaEoGrdRA3YNIfjsfe8CVlHsYAnz03QKBgBc4cZVPDTHQH92FasGOY14tj3rdQnihnQjM1plfRp1Bg6L9fIIeeJmRks/7MVBYDrdvBHaeR5eFFwOu0BxLRjG+Y56+x/6ir68US0Y9pnMFDO4xbJDjrvGEyYC7jPTFK8cRVtPxUpbaf2GsGe/+9YOrtWdQFU11F9FMmTIfibHhAoGATrOj34WLYG+m7BQzKEw4mcbnxEVIAEnRMKq+9zpahxDRo2+oxyABXNKBrgKWSCxjy5PYyY6OCTNzuQpt9aOkyx58TqQgMW8m1XLWZ6MJ9eNDfWWS5eT9qLSyz+ocID3KIWNy2N3wlToRctyOJYfQ3mTgHZasizOBGlVkTggtvi4=' , // /应用私钥
        'ALIPAY_RSA_PBULIC_KEY'=>'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjFt/ONWr8523KqWzI9E99kqx6EayJ4huaKXbcm5i/xc12ufyx3h7LUovjuRdHgUiLbhP6PyjFGC6+tZNcYmoRfwN4SkCDqXh0p8AtvcrjiwlPFZKEzXBCGZkodi/zSuwA/9/2NNGi3Q3l/tDvJpxGd8bvzDnyXt/A1mb0viKZDXjiQsTMSIE5ONHja1QxqZ+9vxxSrOW1PAlmDXTETK6EC5MmWWVkVSziFoaqgErvK3mujkX+gWc/tD6/Y4AdSkkl7qBP23PCSvbNUXFoh6rs8oaHCwsD7LpWJTt3zMupr1KnCNMKliH1VJ0RJHS32JA32zmS3GOPvMGOg2CP8bDuQIDAQAB' , //支付宝公钥
        
    ],

];
