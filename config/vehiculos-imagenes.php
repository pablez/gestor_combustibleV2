<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración del Sistema de Imágenes de Vehículos
    |--------------------------------------------------------------------------
    */

    'disk' => env('VEHICULOS_IMAGE_DISK', 'public'),
    'base_path' => env('VEHICULOS_STORAGE_PATH', 'vehiculos'),
    
    /*
    |--------------------------------------------------------------------------
    | Configuración por Tipo de Imagen
    |--------------------------------------------------------------------------
    */
    'tipos' => [
        'foto_principal' => [
            'nombre' => 'Foto Principal',
            'descripcion' => 'Imagen principal del vehículo, vista frontal o lateral',
            'icono' => '🚗',
            'max_size_kb' => 5120, // 5MB
            'dimensions' => ['width' => 1200, 'height' => 800],
            'quality' => 85,
            'folder' => 'principales',
            'required' => false,
            'multiple' => false,
            'min_width' => 800,
            'min_height' => 600,
        ],
        'galeria_fotos' => [
            'nombre' => 'Galería de Fotos',
            'descripcion' => 'Colección de imágenes del vehículo desde diferentes ángulos',
            'icono' => '📸',
            'max_size_kb' => 3072, // 3MB por imagen
            'dimensions' => ['width' => 1000, 'height' => 750],
            'quality' => 80,
            'folder' => 'galeria',
            'required' => false,
            'multiple' => true,
            'max_files' => 10,
            'min_width' => 640,
            'min_height' => 480,
        ],
        'foto_tarjeton_propiedad' => [
            'nombre' => 'Tarjetón de Propiedad',
            'descripcion' => 'Documento oficial de propiedad del vehículo',
            'icono' => '📋',
            'max_size_kb' => 2048, // 2MB
            'dimensions' => ['width' => 800, 'height' => 600],
            'quality' => 90,
            'folder' => 'documentos',
            'required' => true,
            'multiple' => false,
        ],
        'foto_cedula_identidad' => [
            'nombre' => 'Cédula de Identidad Vehicular',
            'descripcion' => 'Documento de identificación del vehículo',
            'icono' => '🆔',
            'max_size_kb' => 2048,
            'dimensions' => ['width' => 800, 'height' => 600],
            'quality' => 90,
            'folder' => 'documentos',
            'required' => true,
            'multiple' => false,
        ],
        'foto_seguro' => [
            'nombre' => 'Seguro Vehicular',
            'descripcion' => 'Póliza de seguro vigente del vehículo',
            'icono' => '🛡',
            'max_size_kb' => 2048,
            'dimensions' => ['width' => 800, 'height' => 600],
            'quality' => 90,
            'folder' => 'documentos',
            'required' => true,
            'multiple' => false,
        ],
        'foto_revision_tecnica' => [
            'nombre' => 'Revisión Técnica',
            'descripcion' => 'Certificado de revisión técnica vehicular',
            'icono' => '🔧',
            'max_size_kb' => 2048,
            'dimensions' => ['width' => 800, 'height' => 600],
            'quality' => 90,
            'folder' => 'documentos',
            'required' => true,
            'multiple' => false,
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Formatos Permitidos
    |--------------------------------------------------------------------------
    */
    'formatos_permitidos' => ['jpg', 'jpeg', 'png', 'webp'],
    'mime_types_permitidos' => [
        'image/jpeg',
        'image/png', 
        'image/webp'
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Almacenamiento
    |--------------------------------------------------------------------------
    */
    'optimization' => [
        'enabled' => true,
        'quality' => 85,
        'auto_orient' => true,
        'strip_exif' => true,
        'progressive' => true,
        'thumbnail_size' => 300,
    ],

    /*
    |--------------------------------------------------------------------------
    | Límites del Sistema
    |--------------------------------------------------------------------------
    */
    'limits' => [
        'max_total_size_per_vehicle_mb' => 50,
        'max_files_per_type' => 10,
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Rutas
    |--------------------------------------------------------------------------
    */
    'paths' => [
        'storage' => 'vehiculos',
        'temp' => 'temp/vehiculos',
        'thumbnails' => 'vehiculos/thumbnails',
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Validación
    |--------------------------------------------------------------------------
    */
    'validation' => [
        'min_image_width' => 200,
        'min_image_height' => 200,
        'max_image_width' => 4096,
        'max_image_height' => 4096,
        'max_file_size_kb' => 10240, // 10MB máximo absoluto
    ],

    /*
    |--------------------------------------------------------------------------
    | Configuración de Metadatos
    |--------------------------------------------------------------------------
    */
    'metadata' => [
        'track_user_uploads' => true,
        'track_file_modifications' => true,
        'store_original_filename' => true,
        'store_file_hash' => true,
    ],
];