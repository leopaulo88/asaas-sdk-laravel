<?php

namespace Leopaulo88\AsaasSdkLaravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Leopaulo88\AsaasSdkLaravel\Commands\AsaasCommand;

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
            ->name('asaas-sdk-laravel')
            ->hasConfigFile('asaas')
            ->hasCommand(AsaasCommand::class);
    }

    public function packageRegistered()
    {
        // Registra o binding para a Facade
        $this->app->bind('asaas', function () {
            return new Asaas();
        });
    }
}
