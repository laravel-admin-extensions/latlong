<?php

namespace Encore\Admin\Latlong;

use Encore\Admin\Admin;
use Encore\Admin\Assets;
use Encore\Admin\Form;
use Encore\Admin\Show;
use Illuminate\Support\ServiceProvider;

class LatlongServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Extension $extension)
    {
        if (! Extension::boot()) {
            return ;
        }

        $this->loadViewsFrom($extension->views(), 'laravel-admin-latlong');

        Assets::define('async', [
            'js' => '//cdn.jsdelivr.net/npm/requirejs-plugins@1.0.2/src/async',
        ]);

        Admin::booting(function () {
            Form::extend('latlong', Latlong::class);
            Show\Field::macro('latlong', Extension::showField());
        });
    }
}
