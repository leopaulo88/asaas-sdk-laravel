<?php

namespace Leopaulo88\Asaas;

use Leopaulo88\Asaas\Commands\AsaasCommand;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
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
            ->name('asaas-sdk-laravel')
            ->hasConfigFile('asaas')
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->setName('asaas:install')
                    ->publishConfigFile()
                    ->askToStarRepoOnGitHub('leopaulo88/asaas-sdk-laravel');
            });
    }

    public function packageRegistered()
    {
        $this->app->bind('asaas', function () {
            return new Asaas;
        });
    }
}
