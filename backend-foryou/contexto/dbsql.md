-- WARNING: This schema is for context only and is not meant to be run.
-- Table order and constraints may not be valid for execution.

CREATE TABLE public.clientes (
  nombre character varying,
  usuario character varying UNIQUE,
  password_hash character varying,
  telefono character varying,
  domicilio character varying,
  correo character varying UNIQUE,
  auth_user_id uuid,
  id_cliente uuid NOT NULL DEFAULT auth.uid(),
  CONSTRAINT clientes_pkey PRIMARY KEY (id_cliente),
  CONSTRAINT clientes_auth_user_id_fkey FOREIGN KEY (auth_user_id) REFERENCES auth.users(id),
  CONSTRAINT clientes_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES auth.users(id)
);
CREATE TABLE public.cotizaciones (
  id_cotizacion bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  id_solicitud bigint NOT NULL,
  fecha_cot timestamp with time zone NOT NULL DEFAULT now(),
  costo_total numeric NOT NULL DEFAULT 0.00,
  notas character varying,
  CONSTRAINT cotizaciones_pkey PRIMARY KEY (id_cotizacion),
  CONSTRAINT fk_cotizaciones_solicitudes FOREIGN KEY (id_solicitud) REFERENCES public.solicitudes(id_solicitud)
);
CREATE TABLE public.detalles_solicitud (
  id_detalles bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  id_solicitud bigint NOT NULL,
  med_alt numeric,
  med_anc numeric,
  descripcion text,
  CONSTRAINT detalles_solicitud_pkey PRIMARY KEY (id_detalles),
  CONSTRAINT fk_detalles_solicitud FOREIGN KEY (id_solicitud) REFERENCES public.solicitudes(id_solicitud)
);
CREATE TABLE public.empleados (
  id_empleado bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  nombre character varying NOT NULL,
  telefono character varying,
  correo character varying,
  password_hash character varying,
  rol character varying,
  CONSTRAINT empleados_pkey PRIMARY KEY (id_empleado)
);
CREATE TABLE public.material_proyecto (
  id_proyecto bigint NOT NULL,
  id_material bigint NOT NULL,
  cant_usada numeric NOT NULL DEFAULT 0.00,
  CONSTRAINT material_proyecto_pkey PRIMARY KEY (id_proyecto, id_material),
  CONSTRAINT fk_mp_proyectos FOREIGN KEY (id_proyecto) REFERENCES public.proyectos(id_proyecto),
  CONSTRAINT fk_mp_materiales FOREIGN KEY (id_material) REFERENCES public.materiales(id_material)
);
CREATE TABLE public.materiales (
  id_material bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  nombre character varying NOT NULL,
  stock numeric NOT NULL DEFAULT 0.00,
  costo_unitario numeric NOT NULL DEFAULT 0.00,
  CONSTRAINT materiales_pkey PRIMARY KEY (id_material)
);
CREATE TABLE public.materiales_cotizacion (
  id_mat bigint NOT NULL,
  id_cot bigint NOT NULL,
  cant_usa numeric NOT NULL DEFAULT 0.00,
  costo_unitario numeric,
  CONSTRAINT materiales_cotizacion_pkey PRIMARY KEY (id_mat, id_cot),
  CONSTRAINT fk_mc_materiales FOREIGN KEY (id_mat) REFERENCES public.materiales(id_material),
  CONSTRAINT fk_mc_cotizaciones FOREIGN KEY (id_cot) REFERENCES public.cotizaciones(id_cotizacion)
);
CREATE TABLE public.pagos (
  id_pago bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  id_cot bigint NOT NULL,
  metodo character varying NOT NULL,
  fec_pago timestamp with time zone NOT NULL DEFAULT now(),
  cantidad numeric NOT NULL DEFAULT 0.00,
  CONSTRAINT pagos_pkey PRIMARY KEY (id_pago),
  CONSTRAINT fk_pagos_cotizaciones FOREIGN KEY (id_cot) REFERENCES public.cotizaciones(id_cotizacion)
);
CREATE TABLE public.proyectos (
  id_proyecto bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  observaciones character varying,
  plano_url character varying,
  plano_json jsonb,
  id_cliente uuid,
  CONSTRAINT proyectos_pkey PRIMARY KEY (id_proyecto),
  CONSTRAINT fk_proyectos_clientes FOREIGN KEY (id_cliente) REFERENCES public.clientes(id_cliente)
);
CREATE TABLE public.solicitudes (
  id_solicitud bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  direccion character varying NOT NULL,
  descripcion text,
  fecha timestamp with time zone NOT NULL DEFAULT now(),
  id_cliente uuid,
  dias_disponibles text,
  fecha_cita timestamp with time zone,
  materiales text,
  tipo_proyecto text,
  CONSTRAINT solicitudes_pkey PRIMARY KEY (id_solicitud),
  CONSTRAINT fk_solicitudes_clientes FOREIGN KEY (id_cliente) REFERENCES public.clientes(id_cliente)
);
CREATE TABLE public.visitas (
  id_visita bigint GENERATED ALWAYS AS IDENTITY NOT NULL,
  id_empleado bigint NOT NULL,
  id_solicitud bigint NOT NULL,
  fecha timestamp with time zone NOT NULL DEFAULT now(),
  observaciones character varying,
  CONSTRAINT visitas_pkey PRIMARY KEY (id_visita),
  CONSTRAINT fk_visitas_empleados FOREIGN KEY (id_empleado) REFERENCES public.empleados(id_empleado),
  CONSTRAINT fk_visitas_solicitudes FOREIGN KEY (id_solicitud) REFERENCES public.solicitudes(id_solicitud)
);