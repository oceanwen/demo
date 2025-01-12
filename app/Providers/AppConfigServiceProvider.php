<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Aws\SecretsManager\SecretsManagerClient;
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
        $client = new SecretsManagerClient([
            'version' => 'latest',
            'region' => 'ap-northeast-1',
        ]);

        $secretName = 'aksk';
        $result = $client->getSecretValue([
            'SecretId' => $secretName,
        ]);

        $secret = json_decode($result['SecretString'], true);
        $awsAccessKeyId = $secret['AWS_ACCESS_KEY_ID'];
        $awsSecretAccessKey = $secret['AWS_SECRET_ACCESS_KEY'];

        dump($awsAccessKeyId,$awsSecretAccessKey);
        
        $appConfig = new AppConfigClient([
            'version' => 'latest',
            'region' => 'ap-northeast-1',
            'credentials' => [
                'key'    => $awsAccessKeyId,
                'secret' => $awsSecretAccessKey,
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
