<?php

namespace Hubooai\Asaas;

use Hubooai\Asaas\Commands\AsaasCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AsaasServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('asaas')
            ->hasConfigFile('asaas')
            ->hasCommand(AsaasCommand::class);
    }

    public function packageRegistered()
    {
        // Register as a regular binding instead of singleton to allow multiple instances
        $this->app->bind('asaas', function () {
            return new Asaas();
        });

        // Also register a factory method for creating instances with different configurations
        $this->app->bind('asaas.factory', function () {
            return new AsaasFactory();
        });
    }
}
