<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'sys Farmacia',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>sys</b>Farmacia',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#71-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#721-authentication-views-classes
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#722-admin-panel-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#73-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#74-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#92-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#8-menu-configuration
    |
    */
    
    'menu' => [
        [
            'text'          => 'Lotes Vencidos',
            'url'           => '/reporteLotesVencimiento',
            'icon'          => 'fas fa-fw fa-receipt',
            'icon_color'    => 'warning',
            'id'            => 'ilotevenc',
            'topnav_right'  => 'right',            
            'label_color'   => 'warning',
        ],
        [
            'text'          => 'Ventas',
            'url'           => '/reporteVentaDia',
            'icon'          => 'fas fa-fw fa-cart-plus',
            'icon_color'    => 'green',
            'id'            => 'iventas',
            'topnav_right'  => 'right',
            'label_color'   => 'success',
        ],    
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],        
        ['header' => 'Módulos'],
        [
            'text' => 'Empresa',
            'url'  => '/empresa',
            'icon' => 'fas fa-fw fa-hospital',
            'submenu' => [
                [
                    'text'  => 'Empresa',
                    'url'   =>  '/empresa/1/edit',
                    'icon'  =>  'fas fa-fw fa-hospital',
                ],
                [
                    'text'  =>  'Sucursales',
                    'url'   =>  '/agencia',
                    'icon'  =>  'fas fa-fw fa-clinic-medical',
                ],
                [
                    'text'  =>  'Puntos de Venta',
                    'url'   =>  '/puntoventa',
                    'icon'  =>  'fas fa-fw fa-cash-register',
                ],
            ]
        ],
        [
            'text' => 'Clientes',
            'url'  => '/cliente',
            'icon' => 'fas fa-fw fa-user',
            'icon_color' => 'white',
        ],
        [
            'text'        => 'Farmacología',
            'icon_color'  => 'cyan',
            'icon'        => 'fas fa-fw fa-prescription-bottle-alt',
            'submenu' => [                
                [
                    'text' => 'Medicamentos',
                    'url'  => '/medicamento',
                    'icon' => 'fas fa-fw fa-medkit',
                ],
                [
                    'text' => 'Acciones Terapéuticas',
                    'url'  => '/clase',
                    'icon' => 'fas fa-fw fa-notes-medical'
                ],      
                [
                    'text' => 'Presentaciones',
                    'url'  => '/formato',
                    'icon' => 'fas fa-fw fa-pills',
                ], 
                [
                    'text'       => 'Posología',
                    'icon'       => 'fas fa-fw fa-eye-dropper',
                    'icon_color' => 'orange',
                    'url'        => '#',
                    'submenu' => [
                        [
                            'text' => 'Dosis',
                            'url'  => '/medida',
                            'icon' => 'fas fa-fw fa-vial',
                        ],
                        [
                            'text' => 'Vías Administración',
                            'url'  => '/via',
                            'icon' => 'fas fa-fw fa-syringe',
                        ],
                    ],
                ],                        
            ],
        ],        
        [
            'text'       => 'Laboratorios',
            'icon'       => 'fas fa-fw fa-flask',
            'icon_color' => 'green',
            'url'        => '/laboratorio',
        ],
        [
            'text'       => 'Lotes',
            'icon'       => 'fas fa-fw fa-receipt',
            'icon_color' => 'yellow',
            'url'        => '/lote',
        ],
        [
            'text'       => 'Ingresos',
            'icon'       => 'fas fa-sign-in-alt',
            'icon_color' => 'red',
            'url'        => '#',
            'submenu'    => [
                [
                    'text'  =>  'Proveedor',
                    'url'   =>  '/agente',
                    'icon'  =>  'fas fa-fw fa-ambulance',
                ],
                [
                    'text'  =>  'Compra',
                    'url'   =>  '/compra',
                    'icon'  =>  'fas fa-fw fa-tag',
                ],
                [
                    'text'  =>  'Salida',
                    'url'   =>  '/compra/0/salida',
                    'icon'  =>  'fas fa-arrow-circle-up',

                ]
            ],
        ],
        [
            'text'       => 'Egresos',
            'icon'       => 'fas fa-sign-out-alt',
            'icon_color' => 'blue',
            'url'        => '#',
            'submenu'    => [
                [
                    'text'  =>  'Venta',
                    'url'   =>  '/venta',
                    'icon'  =>  'fas fa-fw fa-tag',
                ],
                [
                    'text'  =>  'Entrada',
                    'url'   =>  '/venta/0/entrada',
                    'icon'  =>  'fas fa-arrow-circle-down'
                ]     
            ],
        ],
        [
            'text'      =>  'Caja',
            'icon'      =>  'fas fa-donate',  
            'icon_color'=>  'yellow',
            'submenu'   =>  [
                [
                    'text'  =>  'Listado de Arqueos',
                    'url'   =>  '/caja',
                    'icon'  =>  'fas fa-fw fa-clone'
                ],
                [
                    'text'  =>  'Apertura de Caja',
                    'url'   =>  '/caja/create',
                    'icon'  =>  'fas fa-fw fa-unlock'
                ],
                [
                    'text'  =>  'Arqueo de Caja',
                    'url'   =>  '/caja/0/edit/',
                    'icon'  =>  'fas fa-fw fa-calculator'
                ]
            ],
        ],

        [
            'text'      =>  'Facturas',
            'icon'      =>  'fas fa-archive',
            'icon_color'=>  'white',
            'submenu'   =>  [
                [
                    'text'  =>  'Facturas',
                    'url'   =>  '/factura',
                    'icon'  =>  'fas fa-inbox'
                ],
                [
                    'text'  =>  'Eventos',
                    'url'   =>  '/evento',
                    'icon'  =>  'fas fa-cubes'
                ]
            ],
        ],
        
        [
            'text'      =>  'Documentos',
            'icon'      =>  'fas fa-fw fa-file',
            'icon_color'=>  'white',
            'submenu'   =>  [
                [
                    'text'  =>  'Exportar',
                    'url'   =>  '/reporte',
                    'icon'  =>  'fas fa-file-export'
                ],
                [
                    'text'  =>  'Importar',
                    'url'   =>  '/importe',
                    'icon'  =>  'fas fa-file-import'
                ]

            ],
        ],
        
        [
            'text'      =>  'Configuracion',
            'icon'      =>  'fas fa-fw fa-cog',
            'icon_color'=>  'white',
            'url'       =>  '/ajuste'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#83-custom-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#91-plugins
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#93-livewire
    */

    'livewire' => false,
];
