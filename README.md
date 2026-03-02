# Adopciones API REST

Desarrollada en Laravel 12 para la gestión de adopciones de animales (perros y gatos), con autenticación mediante Laravel Passport, control de roles y documentación completa con OpenAPI 3.0.3.

## Objetivo del Proyecto

Transformar una aplicación MVC tradicional en una API REST versionada aplicando:
Arquitectura limpia
Autenticación con tokens (Passport)
Control de acceso por roles
Documentación OpenAPI
Testing funcional completo

## Arquitectura

El proyecto sigue una arquitectura desacoplada y profesional:
Controllers → Orquestación de peticiones
Services → Lógica de negocio
Form Requests → Validación
API Resources → Transformación de respuestas
Policies / Middleware → Autorización
OpenAPI Schemas → Documentación estructurada
Feature Tests → Verificación funcional

## Seguridad

La API implementa:
Autenticación mediante Laravel Passport
Tokens Bearer
Middleware auth.api:api
Sistema de roles (admin, adopter)

## Roles

Rol	Permisos
Admin CRUD de animales, aprobar/rechazar solicitudes, acceso a dashboard
Adopter Crear solicitudes y ver las propias
Todas las rutas requieren autenticación.

## Documentación

Documentación generada automáticamente con:
swagger-php v6 (PHP Attributes)
l5-swagger
OpenAPI 3.0.3
Disponible en:
/api/documentation
Endpoints Principales
Animals
GET /api/v1/animals
POST /api/v1/animals
GET /api/v1/animals/{id}
PUT /api/v1/animals/{id}
DELETE /api/v1/animals/{id}
Adoption Requests
GET /api/v1/adoption-requests
POST /api/v1/adoption-requests
PATCH /api/v1/adoption-requests/{id}/approve
PATCH /api/v1/adoption-requests/{id}/reject
PATCH /api/v1/adoption-requests/{id}/cancel
Auth
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout
Admin Dashboard
GET /api/v1/admin/dashboard
Estructura de Respuesta
Todas las respuestas siguen el formato estándar:

{
  "success": true,
  "message": "Optional message",
  "data": {},
  "meta": {}
}

## Reglas de Negocio

Solo admins pueden aprobar o rechazar solicitudes.
Solo el propietario puede cancelar su solicitud.
Una solicitud solo puede aprobarse si está en estado PENDIENTE.
Al aprobar una solicitud, el animal pasa a estado ADOPTADO.
Los usuarios solo pueden ver sus propias solicitudes (excepto admin).

## Testing

La aplicación incluye tests funcionales completos que verifican:
Autenticación con Passport
Restricción por roles
CRUD de animales
Flujo completo de solicitudes de adopción
Transiciones de estado
Validaciones de negocio (422)
Protección de rutas (401 / 403)
Manejo de recursos inexistentes (404)

Estructura de tests
tests/Feature/
│
├── Admin/
│   └── AdminDashboardTest.php
│
├── AdoptionRequests/
│   ├── StoreAdoptionRequestTest.php
│   ├── IndexAdoptionRequestTest.php
│   ├── ApproveAdoptionRequestTest.php
│   ├── RejectAdoptionRequestTest.php
│   └── CancelAdoptionRequestTest.php
│
├── Animals/
│   ├── AnimalIndexTest.php
│   ├── AnimalStoreTest.php
│   ├── AnimalUpdateTest.php
│   └── AnimalDestroyTest.php
│
└── Auth/
    ├── RegisterTest.php
    └── LoginTest.php

### Ejecutar tests

php artisan test
Los tests utilizan:
RefreshDatabase
Factories
SQLite en entorno de testing
actingAs() con guard api
Assertions de base de datos

## Stack Tecnológico

PHP 8.4
Laravel 12
SQLite
Laravel Passport
OpenAPI 3.0.3
Swagger UI

## Instalación

git clone <https://github.com/HebeStark/adopciones-api.git>
cd adopciones-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan passport:install
php artisan serve
