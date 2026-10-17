<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();

        $previewMimes = config('livewire.temporary_file_upload.preview_mimes', []);
        config([
            'livewire.temporary_file_upload.preview_mimes' => array_values(array_unique(array_merge(
                $previewMimes,
                ['avif', 'heic', 'heif']
            ))),
        ]);

        \Illuminate\Support\Facades\View::composer(['layouts.storefront', 'welcome', 'product-detail'], function ($view) {
            if (! isset($view->getData()['categories'])) {
                try {
                    $view->with('categories', \App\Models\Category::active()->orderBy('sort_order')->orderBy('name')->get());
                } catch (\Throwable $e) {
                    $view->with('categories', collect());
                }
            }
        });
    }


    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
