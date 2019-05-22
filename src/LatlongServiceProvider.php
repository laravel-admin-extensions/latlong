<?php

namespace Encore\Admin\Latlong;

use Encore\Admin\Admin;
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

        Admin::booting(function () {
            Form::extend('latlong', Latlong::class);
            Show\Field::macro('latlong', Extension::showField());
        });
    }
}