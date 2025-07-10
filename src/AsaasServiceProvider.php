<?php

namespace Hubooai\Asaas;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Hubooai\Asaas\Commands\AsaasCommand;

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
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_asaas_sdk_laravel_table')
            ->hasCommand(AsaasCommand::class);
    }
}
