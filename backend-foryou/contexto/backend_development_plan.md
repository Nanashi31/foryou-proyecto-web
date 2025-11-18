Fases de Desarrollo del Backend en Laravel para "FORYOU"

### Plan de Desarrollo del Backend en Laravel

#### Fase 1: Configuración Inicial y Autenticación

El objetivo es establecer una base sólida para el proyecto.

1.  **Instalar Laravel:** Crear un nuevo proyecto de Laravel.
2.  **Configurar la Base de Datos:** Conectar el proyecto a tu base de datos (MySQL, PostgreSQL, etc.) a través del archivo `.env`.
3.  **Implementar Autenticación:**
    *   Instalar Laravel Sanctum para la autenticación de API (ya que tendrás una aplicación web como cliente).
    *   Utilizar un paquete de inicio como Laravel Breeze o Jetstream en modo API para generar automáticamente las rutas y controladores para registro, inicio y cierre de sesión. Esto manejará la lógica de `clientes` y `empleados` que necesitan acceder al sistema.

#### Fase 2: Migraciones y Modelos (El Esqueleto de la App)

Traduciremos tu esquema de base de datos (`dbsql.md`) a la estructura de Laravel. Esto es fundamental.

1.  **Crear Migraciones:** Generar un archivo de migración para cada tabla de tu esquema:
    *   `create_clientes_table`
    *   `create_empleados_table`
    *   `create_materiales_table`
    *   `create_solicitudes_table`
    *   `create_detalles_solicitud_table`
    *   `create_visitas_table`
    *   `create_cotizaciones_table`
    *   `create_materiales_cotizacion_table` (tabla pivote)
    *   `create_proyectos_table`
    *   `create_material_proyecto_table` (tabla pivote)
    *   `create_pagos_table`
2.  **Crear Modelos Eloquent:** Crear un modelo para cada tabla principal. Aquí definiremos las relaciones que son clave para que la aplicación funcione de manera eficiente:
    *   **Cliente:** `hasMany` (tiene muchas) `Solicitudes`, `Proyectos`.
    *   **Empleado:** `hasMany` `Visitas`.
    *   **Solicitud:** `belongsTo` (pertenece a) `Cliente`, `hasMany` `DetallesSolicitud`, `hasOne` (tiene una) `Cotizacion`.
    *   **Cotizacion:** `belongsTo` `Solicitud`, `belongsToMany` `Materiales` (a través de `materiales_cotizacion`).
    *   **Proyecto:** `belongsTo` `Cliente`, `belongsToMany` `Materiales` (a través de `material_proyecto`).
    *   **Material:** `belongsToMany` `Cotizaciones`, `Proyectos`.

#### Fase 3: Endpoints de API (La Lógica del Negocio)

Aquí construiremos las rutas de la API que la aplicación frontend consumirá. Lo haremos en un orden lógico, de lo más simple a lo más complejo.

1.  **CRUD para Administración Básica:**
    *   **Materiales:** Endpoints para crear, leer, actualizar y eliminar materiales (`/api/materiales`). Esto es vital para el módulo de inventario.
    *   **Clientes y Empleados:** Endpoints para gestionar usuarios (`/api/clientes`, `/api/empleados`).
2.  **Flujo Principal del Negocio:**
    *   **Solicitudes:** Endpoints para que los clientes creen solicitudes y para que los administradores las vean y gestionen (`/api/solicitudes`).
    *   **Agenda (Visitas):** Endpoints para agendar visitas asociadas a una solicitud (`/api/visitas`).
    *   **Cotizaciones:**
        *   Un endpoint para **generar** una cotización a partir de una solicitud (`POST /api/cotizaciones`).
        *   Endpoints para **agregar/quitar materiales** a una cotización.
        *   Un endpoint para **aprobar** una cotización, lo que cambiará su estado.
3.  **Integración con IA (Característica Clave):**
    *   Crear un controlador o una clase de servicio específica (ej. `AiQuotingController`).
    *   Este tendrá un endpoint (ej. `POST /api/cotizaciones/sugerir-materiales`) que recibirá la descripción del proyecto.
    *   Internamente, este servicio se conectará a una API de IA (como Gemini o GPT), le enviará la descripción del trabajo y analizará la respuesta para sugerir una lista de materiales y cantidades.
    *   El resultado se devolverá como una sugerencia al frontend para que el usuario la confirme.
4.  **Gestión de Proyectos y Pagos:**
    *   Un endpoint para **convertir** una cotización aprobada en un `Proyecto` (`POST /api/proyectos`).
    *   Endpoints para registrar pagos asociados a una cotización (`/api/pagos`).

#### Fase 4: Pruebas y Despliegue

Para asegurar la calidad y el correcto funcionamiento.

1.  **Pruebas (Testing):** Escribir pruebas automatizadas con Pest o PHPUnit para los endpoints más críticos. Por ejemplo:
    *   ¿Se puede crear un usuario?
    *   ¿La creación de una cotización actualiza el stock de materiales?
    *   ¿Un usuario no autorizado puede ver datos de otros clientes?
2.  **Despliegue:** Preparar el proyecto para producción, configurando las variables de entorno (`.env`) y documentando los endpoints (puedes usar herramientas como Scribe para generar documentación automática).
