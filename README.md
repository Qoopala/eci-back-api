<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# ECI - API

## Descripción
Este proyecto es el backend del proyecto EBUSA el cual esta construido como una APIrest usando laravel 10 con php 8. 

## Requisitos Previos
- PHP 8
- Composer instalado
- Base de datos MySql
- Servidor web Apache

## Instalación

- Instalar Composer
```bash
composer install
```

- Crear archivo. env, copiar contenido de example.env y remplazar parámetros necesarios
```bash
cp. env.example. env
```
- Generar Key de laravel
```bash
php artisan key:generate
```
- Crear base de datos en phpMyAdmin con el nombre "eci-db" y ejecutar migraciones con seeder, esto poblara la base de datos con todo lo necesario para comenzar a trabajar con el proyecto. 
```bash
php artisan migrate --seed
```
- Para levantar el proyecto
```bash
php artisan serve
```
- Para levantar el proyecto con ip publica (recomendado para consumir api desde frontend)
```bash
php artisan serve  --host 0.0.0.0
```

## Documentation
En el directorio resource/docs encontrarán:
- [Collection de postman para importar](https://github.com/Qoopala/eci-back-api/blob/main/resources/docs/eci-back-api.postman_collection.json)
- [Diagrama ER para descargar](https://github.com/Qoopala/eci-back-api/blob/main/resources/docs/eci-diagram-db.mwb)

## Api Documentation
### Descripción
Este documento describe cómo interactuar con la API de nuestro proyecto. La API acepta solicitudes en formato JSON y devuelve respuestas en el siguiente formato:

```
{
  "success": true,
  "message": "Mensaje descriptivo aquí",
  "data": {
    "ejemplo": "datos de respuesta aquí"
  }
}
```

### Recursos Disponibles
- Endpoint Ejemplo
```
POST /api/login
```
- Request
```
{
  "parametro1": "valor1",
  "parametro2": "valor2"
}
```

- Respuesta Exitosa
```
{
  "success": true,
  "message": "La acción se completó correctamente",
  "data": {
    "ejemplo": "datos de respuesta aquí"
  }
}
```

- Respuesta con error
```
{
  "success": false,
  "message": "Mensaje de error descriptivo",
  "data": null
}
```



