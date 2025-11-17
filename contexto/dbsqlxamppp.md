-- =====================================================
-- OPCIONAL: crear base de datos y usarla
-- =====================================================
CREATE DATABASE IF NOT EXISTS foryou_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE foryou_db;

-- =====================================================
-- TABLA: clientes
-- Comentario: Clientes finales de la herrería.
-- En Supabase el id_cliente venía de auth.users (uuid).
-- Aquí lo generamos con UUID() directamente en MySQL.
-- =====================================================
CREATE TABLE clientes (
  -- Identificador del cliente en formato UUID
  id_cliente CHAR(36) NOT NULL DEFAULT (UUID()),
  
  -- Nombre del cliente
  nombre VARCHAR(255) NOT NULL,
  
  -- Usuario único para login
  usuario VARCHAR(255) UNIQUE,
  
  -- Contraseña hasheada
  password_hash VARCHAR(255) NOT NULL,
  
  -- Teléfono de contacto
  telefono VARCHAR(50),
  
  -- Dirección del cliente
  domicilio VARCHAR(255),
  
  -- Correo electrónico único
  correo VARCHAR(255) UNIQUE,
  
  -- (Opcional) id del usuario en algún sistema de auth externo
  auth_user_id CHAR(36),

  PRIMARY KEY (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: empleados
-- Comentario: Personal (administradores, herreros, etc.).
-- =====================================================
CREATE TABLE empleados (
  -- Identificador interno de empleado
  id_empleado BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Nombre completo del empleado
  nombre VARCHAR(255) NOT NULL,
  
  -- Teléfono de contacto
  telefono VARCHAR(50),
  
  -- Correo del empleado
  correo VARCHAR(255),
  
  -- Contraseña hasheada (si el empleado inicia sesión)
  password_hash VARCHAR(255),
  
  -- Rol del empleado (admin, herrero, etc.)
  rol VARCHAR(100),
  
  PRIMARY KEY (id_empleado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: materiales
-- Comentario: Catálogo de materiales disponibles.
-- =====================================================
CREATE TABLE materiales (
  -- Identificador del material
  id_material BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Nombre del material (ej. "Varilla 3/8")
  nombre VARCHAR(255) NOT NULL,
  
  -- Stock disponible
  stock DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  
  -- Costo unitario del material
  costo_unitario DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  
  PRIMARY KEY (id_material)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: solicitudes
-- Comentario: Solicitudes de servicio hechas por los clientes.
-- =====================================================
CREATE TABLE solicitudes (
  -- Identificador de la solicitud
  id_solicitud BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Dirección donde se realizará el trabajo
  direccion VARCHAR(255) NOT NULL,
  
  -- Descripción general del proyecto solicitado
  descripcion TEXT,
  
  -- Fecha en que se creó la solicitud
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  -- Cliente que hace la solicitud
  id_cliente CHAR(36),
  
  -- Días disponibles del cliente (texto libre)
  dias_disponibles TEXT,
  
  -- Fecha y hora programada para la cita de visita
  fecha_cita DATETIME,
  
  -- Lista de materiales sugeridos (texto libre)
  materiales TEXT,
  
  -- Tipo de proyecto (ej. puerta, ventana, barandal, etc.)
  tipo_proyecto TEXT,
  
  PRIMARY KEY (id_solicitud),
  CONSTRAINT fk_solicitudes_clientes
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: proyectos
-- Comentario: Proyectos ya formales asociados a clientes.
-- =====================================================
CREATE TABLE proyectos (
  -- Identificador del proyecto
  id_proyecto BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Observaciones generales del proyecto
  observaciones VARCHAR(255),
  
  -- URL de algún plano (imagen/PDF)
  plano_url VARCHAR(500),
  
  -- Definición del plano en formato JSON (para diseño digital)
  plano_json JSON,
  
  -- Cliente dueño del proyecto
  id_cliente CHAR(36),
  
  PRIMARY KEY (id_proyecto),
  CONSTRAINT fk_proyectos_clientes
    FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente)
    ON UPDATE CASCADE
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: cotizaciones
-- Comentario: Cotizaciones generadas a partir de una solicitud.
-- =====================================================
CREATE TABLE cotizaciones (
  -- Identificador de la cotización
  id_cotizacion BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Solicitud de la que nace la cotización
  id_solicitud BIGINT NOT NULL,
  
  -- Fecha de la cotización
  fecha_cot DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  -- Costo total de la cotización
  costo_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  
  -- Notas adicionales de la cotización
  notas VARCHAR(255),
  
  PRIMARY KEY (id_cotizacion),
  CONSTRAINT fk_cotizaciones_solicitudes
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: detalles_solicitud
-- Comentario: Detalles por pieza/zona de una solicitud
-- (medidas y descripción por cada elemento).
-- =====================================================
CREATE TABLE detalles_solicitud (
  -- Identificador del detalle
  id_detalles BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Relación con la solicitud
  id_solicitud BIGINT NOT NULL,
  
  -- Medida de alto
  med_alt DECIMAL(10,2),
  
  -- Medida de ancho
  med_anc DECIMAL(10,2),
  
  -- Descripción del elemento (ej. "ventana frontal")
  descripcion TEXT,
  
  PRIMARY KEY (id_detalles),
  CONSTRAINT fk_detalles_solicitud
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: material_proyecto
-- Comentario: Materiales usados en cada proyecto (relación N:N).
-- =====================================================
CREATE TABLE material_proyecto (
  -- Proyecto al que se asocia el material
  id_proyecto BIGINT NOT NULL,
  
  -- Material utilizado
  id_material BIGINT NOT NULL,
  
  -- Cantidad usada de ese material
  cant_usada DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  
  PRIMARY KEY (id_proyecto, id_material),
  CONSTRAINT fk_mp_proyectos
    FOREIGN KEY (id_proyecto) REFERENCES proyectos(id_proyecto)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_mp_materiales
    FOREIGN KEY (id_material) REFERENCES materiales(id_material)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: materiales_cotizacion
-- Comentario: Materiales contemplados en una cotización
-- (relación N:N entre materiales y cotizaciones).
-- =====================================================
CREATE TABLE materiales_cotizacion (
  -- Material cotizado
  id_mat BIGINT NOT NULL,
  
  -- Cotización donde se usa
  id_cot BIGINT NOT NULL,
  
  -- Cantidad estimada a usar
  cant_usa DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  
  -- Costo unitario al momento de cotizar (puede diferir del actual)
  costo_unitario DECIMAL(10,2),
  
  PRIMARY KEY (id_mat, id_cot),
  CONSTRAINT fk_mc_materiales
    FOREIGN KEY (id_mat) REFERENCES materiales(id_material)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT fk_mc_cotizaciones
    FOREIGN KEY (id_cot) REFERENCES cotizaciones(id_cotizacion)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: pagos
-- Comentario: Pagos realizados sobre una cotización.
-- =====================================================
CREATE TABLE pagos (
  -- Identificador del pago
  id_pago BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Cotización a la que corresponde el pago
  id_cot BIGINT NOT NULL,
  
  -- Método de pago (efectivo, transferencia, etc.)
  metodo VARCHAR(100) NOT NULL,
  
  -- Fecha del pago
  fec_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  -- Cantidad pagada
  cantidad DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  
  PRIMARY KEY (id_pago),
  CONSTRAINT fk_pagos_cotizaciones
    FOREIGN KEY (id_cot) REFERENCES cotizaciones(id_cotizacion)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLA: visitas
-- Comentario: Visitas de empleados a las solicitudes
-- (para medir, revisar, etc.).
-- =====================================================
CREATE TABLE visitas (
  -- Identificador de la visita
  id_visita BIGINT NOT NULL AUTO_INCREMENT,
  
  -- Empleado que realiza la visita
  id_empleado BIGINT NOT NULL,
  
  -- Solicitud visitada
  id_solicitud BIGINT NOT NULL,
  
  -- Fecha y hora de la visita
  fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  
  -- Observaciones de la visita
  observaciones VARCHAR(255),
  
  PRIMARY KEY (id_visita),
  CONSTRAINT fk_visitas_empleados
    FOREIGN KEY (id_empleado) REFERENCES empleados(id_empleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_visitas_solicitudes
    FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
