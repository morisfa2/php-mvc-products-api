<?php

declare(strict_types=1);

namespace App\Config;

use MongoDB\Client;

class Database
{
    private static ?Client $client = null;

    public static function getClient(): Client
    {
        if (self::$client === null) {
            $uri = sprintf(
                'mongodb://%s:%s@%s:%s',
                $_ENV['MONGO_USERNAME'],
                $_ENV['MONGO_PASSWORD'],
                $_ENV['MONGO_HOST'],
                $_ENV['MONGO_PORT']
            );
            
            self::$client = new Client($uri);
        }

        return self::$client;
    }

    public static function getDatabase(): string
    {
        return $_ENV['MONGO_DATABASE'];
    }
} 