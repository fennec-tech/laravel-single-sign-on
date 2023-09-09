<?php

namespace Fennectech\LaravelSingleSignOn;

use Fennectech\LaravelSingleSignOn\Commands\LaravelSingleSignOnCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSingleSignOnServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-single-sign-on')
            ->hasConfigFile()
            ->hasCommand(LaravelSingleSignOnCommand::class);
    }
}
