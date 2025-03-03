<?php

// config/filament.php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Filament will use to put media. You may use any
    | of the disks defined in the `config/filesystems.php`.
    |
    */
    'default_filesystem_disk' => env('FILAMENT_FILESYSTEM_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can configure the user menu to override the default behavior
    | of getting the authenticated user's name.
    |
    */
    'user_menu' => [
        'is_enabled' => true,
        // This setting ensures the first_name is always used
        'name' => fn (): string => auth()->user()->first_name ?? 'Admin',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    |
    | This is the authentication config for your application
    |
    */
    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'username_attribute' => env('FILAMENT_USERNAME_ATTRIBUTE', 'email'),
        // Set a fallback name for users who have null name fields
        'user_name_attribute' => 'first_name',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Database
    |--------------------------------------------------------------------------
    |
    | This is the config for database settings
    |
    */
    'database' => [
        'rules' => [
            'string' => null,
        ],
    ],
];