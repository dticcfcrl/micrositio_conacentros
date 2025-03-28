<?php

namespace Meridiano\Catalogs;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Statamic;
use Illuminate\Pagination\Paginator;
use Statamic\Fieldtypes\Bard;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $publishables = [
        __DIR__.'/../resources/svg' => 'svg',
    ];

    public function boot()
    {
        parent::boot();
        Bard::register();

        Statamic::booted(function () {
            $this->loadViewsFrom(__DIR__.'/../resources/views', 'catalog');
            Paginator::defaultView('catalog::pagination.tailwind');
            Paginator::defaultSimpleView('catalog::pagination.tailwind');

            Nav::extend(function ($nav) {
                $nav->content('Catálogos')
                    ->route('catalog.index')
                    ->icon('book-pages')
                    ->can('view catalogo')
                    ->children([
                        $nav->item('Calculadora')->route('catalog.calculadora.index')->can('view calculadora'),
                        $nav->item('Chatbot')->route('catalog.chatbot.index')->can('view chatbot'),
                    ]);
                $nav->content('Servidor')
                ->route('server.index')
                ->icon('synchronize')
                ->can('view server')
                ->children([
                    $nav->item('Configuración')->route('server.index')->can('view config'),
                ]);
            });

            Permission::register('view catalogo', function ($permission) {
                $permission->children([
                    Permission::make('view calculadora')->label('Ver Catálogo Calculadora'),
                    Permission::make('view chatbot')->label('Ver Catálogo Chatbot'),
                ]);
            })->label('Ver Catálogo');

            Permission::register('view server')->label('Ver Configuración Servidor');
        });
    }
}
