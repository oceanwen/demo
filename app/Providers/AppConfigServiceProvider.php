<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\AppConfig\AppConfigClient;
use Illuminate\Support\Facades\Config;

class AppConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $appConfig = new AppConfigClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ]
        ]);

        // 获取数据库配置
        $response = $appConfig->getConfiguration([
            'Application' => 'gbxdxm7',
            'Environment' => '0x7uptu',
            'Configuration' => 'hxt2ygg',
            'ClientId' => 'laravel-client',
        ]);

        $resultData = $response->toArray();
        $contentStream = $resultData['Content'];

        $content = $contentStream->getContents();
        $configData = json_decode($content, true);

        dump($configData);
        if (!empty($configData)) {
            Config::set('database.connections.mysql.host', $configData['host']);
            Config::set('database.connections.mysql.database', $configData['dbname']);
            Config::set('database.connections.mysql.username', $configData['username']);
            Config::set('database.connections.mysql.password', $configData['password']);
        }
    }
}