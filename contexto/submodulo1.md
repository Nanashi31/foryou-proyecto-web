🧩 Submódulo 1: Administración de Personal y Vehículos
🎯 Objetivo

Gestionar el personal operativo y administrativo, asignar vehículos, controlar mantenimientos y registrar disponibilidad para trabajos o entregas.

⚙️ Características principales

Gestión de empleados

Registro de empleados: nombre, puesto, área, tipo de contrato, salario, CURP, teléfono, correo.

Historial laboral (altas, bajas, ascensos, capacitaciones).

Estado actual: activo, suspendido, baja.

Roles y permisos (acceso a módulos del sistema).

Evaluaciones periódicas (rendimiento, puntualidad, calidad de trabajo).

Asistencia y disponibilidad

Control de asistencia (manual o con QR).

Calendario de turnos y días libres.

Registro de horas trabajadas por proyecto o cotización.

Reportes de ausencias y puntualidad.

Gestión de vehículos

Registro de unidades: marca, modelo, placas, tipo (camioneta, tráiler, etc.).

Asignación de vehículo a empleado o cuadrilla.

Control de mantenimiento preventivo y correctivo.

Bitácora de uso (fecha, conductor, destino, kilometraje, combustible, observaciones).

Alertas por mantenimiento, vencimiento de seguro o verificación.

Seguridad y documentos

Almacenamiento de licencias, pólizas, comprobantes.

Vencimientos con recordatorios automáticos.

Indicadores

Horas trabajadas por empleado y por proyecto.

Costo laboral acumulado mensual.

% de disponibilidad de vehículos.

Mantenimientos realizados vs programados.

🧾 Datos principales

employees: id, nombre, puesto, salario, fecha_ingreso, estado, contacto.

employee_roles: permisos y nivel de acceso.

attendance: empleado_id, fecha, hora_entrada, hora_salida, horas_totales, proyecto_asignado.

vehicles: id, marca, modelo, año, placas, tipo, estado, km_actual, proximo_mantenimiento.

vehicle_logs: vehículo_id, conductor_id, fecha, destino, km_inicio, km_fin, consumo_combustible, notas.

maintenance: vehículo_id, tipo, costo, fecha, proveedor, observaciones.

documents: empleado/vehículo_id, tipo_doc, fecha_vencimiento, archivo.

🔗 Integraciones

Producción y Cotizaciones: asignar personal y vehículos a proyectos u órdenes.

Finanzas: control de costos laborales y de transporte.

Notificaciones: alertas automáticas de vencimientos o mantenimiento.

🧩 Submódulo 2: Administración de Materiales
🎯 Objetivo

Controlar entradas, salidas, costos, ubicaciones y disponibilidad de materiales e insumos del taller, vinculando inventario con cotizaciones y producción.

⚙️ Características principales

Catálogo de materiales

Materias primas (PTR, solera, ángulo, pintura, vidrio, tornillería).

Categorías y unidades de medida.

Código de identificación, descripción, proveedor, costo unitario.

Mínimos y máximos configurables.

Gestión de inventario

Entradas (compras, devoluciones).

Salidas (producción, consumo, merma).

Traspasos entre almacenes o áreas.

Conteos físicos y ajustes.

Control de costos

Costo promedio, último costo y costo estándar.

Historial de costos por proveedor.

Revalorización controlada con permisos especiales.

Alertas y sugerencias

Stock mínimo alcanzado.

Material próximo a caducar (si aplica).

Sugerencias automáticas de reabasto.

Reportes

Kardex por material.

Valuación de inventario.

Consumo por proyecto u OT.

Comparativo de consumo teórico vs real.

🧾 Datos principales

materials: id, código, nombre, unidad, categoría, costo, stock_min, stock_max, activo.

warehouses: id, nombre, ubicación, responsable.

stock: material_id, warehouse_id, cantidad_actual, reservado, costo_promedio.

movements: id, material_id, tipo (entrada/salida/ajuste), cantidad, referencia, usuario, fecha.

suppliers: id, nombre, contacto, teléfono, correo.

purchases: proveedor_id, material_id, cantidad, costo_unitario, fecha_compra.

waste: material_id, tipo, cantidad, fecha, observación.

🔗 Integraciones

Cotizaciones: usa precios y existencias actualizadas.

Diseño: obtiene BOM y materiales para validar disponibilidad.

Producción: descuenta consumo real y genera reportes de merma.

Finanzas: sincroniza costos y valorizaciones.

📈 Indicadores (KPIs)

% de materiales con stock crítico.

Tiempo medio de reabasto.

Merma acumulada mensual.

Desviación entre consumo estimado y real.

Costo total del inventario actual.