<?php
namespace App\Config;

class App {
    /**
     * Get the Application Environment
     */
    public static function env(): string {
        return $_ENV['APP_ENV'] ?? 'development';
    }
    
    /**
     * Base URL of the application
     */
    public static function baseUrl(): string {
        return $_ENV['APP_URL'] ?? 'http://localhost/ToolBox';
    }

    // Site Details
    public static function siteName(): string {
        return $_ENV['APP_NAME'] ?? 'ToolBox';
    }

    public static function supportEmail(): string {
        return $_ENV['APP_SUPPORT_EMAIL'] ?? 'support@pkbehera.in';
    }
    
    public const SITE_AUTHOR = 'PK Behera';
    
    // App version for cache busting
    public const VERSION = '1.0.0';

    /**
     * Get full URL for an asset or path
     */
    public static function url(string $path = ''): string {
        return self::baseUrl() . '/' . ltrim($path, '/');
    }

}
