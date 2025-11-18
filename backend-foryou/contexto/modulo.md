🧱 Módulo: Cotizaciones
🎯 Objetivo

Generar y administrar cotizaciones precisas, trazables y vinculadas con los módulos de Diseño, Inventario y Producción.

⚙️ Características principales

Ficha de cotización

Folio automático (Q-0001, Q-0002, etc.)

Cliente, contacto y datos de envío

Fecha de creación, vigencia y responsable

Origen de cotización

Desde un Diseño existente (importa materiales, tiempos y procesos)

O bien manual, creada desde cero

Partidas

Materiales (código, descripción, unidad, cantidad, costo unitario)

Mano de obra (actividad, horas, tarifa/hora)

Procesos externos (pintura, galvanizado, corte láser, etc.)

Cálculos automáticos

Subtotal, descuentos, impuestos, total

Overhead (%) y margen (%) configurables

Aplicación automática de IVA u otros impuestos

Versionado de cotización

Manejo de revisiones (R0, R1, R2, etc.)

Historial de cambios en precios, cantidades o márgenes

Aprobación del cliente

Enlace o PDF con botón “Aprobar / Solicitar cambios”

Registro de fecha, nombre y observaciones

Conversión

Cotización aprobada → Orden de trabajo (OT)

Reserva automática de materiales en Inventario

Adjuntos

Planos, imágenes de referencia, PDF de cotización

🧾 Datos principales

ID, folio, cliente, contacto

Fecha de emisión y vigencia

Estatus (Borrador, Enviado, Aprobado, Convertido, Cerrado)

Totales: materiales, MO, procesos, impuestos, total

Comentarios, notas y condiciones

🔁 Estados

Borrador → cotización en edición

En revisión → validación interna

Enviada → enviada al cliente

Aprobada → cliente la acepta

Convertida → se genera OT o pedido

Cerrada → finalizada o facturada

📊 Integraciones con otros módulos

Diseño → importar materiales, medidas y tiempos

Inventario → verificar costos y existencias

Producción → generar orden de trabajo

Clientes (CRM) → seguimiento y recordatorios de vigencia

Finanzas → gestionar anticipos y facturación

🧠 Validaciones

No se puede enviar sin cliente ni partidas

Vigencia mínima configurada

Margen mínimo según tipo de producto

Bloquear conversión si no está aprobada

📈 Indicadores (KPIs)

Tasa de aprobación

Tiempo promedio de aprobación

Margen de ganancia promedio

% de cotizaciones convertidas a orden

Comparación costo cotizado vs real

🧱 Módulo: Inventario (Administración)
🎯 Objetivo

Controlar existencias, costos y movimientos de materiales, consumibles y productos semi/terminados; asegurar trazabilidad desde compra → almacén → producción → venta.

⚙️ Características principales

Catálogo de ítems

Materias primas (PTR, ángulo, solera, placa, vidrio, pintura, tornillería).

Consumibles (discos, electrodos, gas).

Sub-ensambles y productos terminados.

Campos: código, descripción, unidad, familia, proveedor preferente, foto, mínimos/máximos, lote/serie si aplica.

Kardex y movimientos

Entradas (compras, devoluciones).

Salidas (a producción, mermas, ajustes).

Traspasos entre almacenes.

Soporte para motivos y documento de referencia (OC, OT, ajuste).

Niveles de stock

Stock actual, comprometido (reservas), disponible.

Alertas por stock mínimo y por obsolescencia.

Costos

Costo estándar, último costo y costo promedio ponderado (CPP).

Historial de costos por fecha/proveedor.

Revalorización controlada (con permisos).

Ubicaciones y multi-almacén

Múltiples almacenes (principal, corte, pintura, obra).

Ubicaciones (pasillo/estante/caja).

Transferencias con autorización.

Reservas y consumos por OT

Reserva desde Cotización/OT.

Descuento de materiales por lista de corte (consumo real vs teórico).

Devoluciones a almacén.

Mermas y desperdicio

Registro de merma por tipo (corte, daño, obsolescencia).

Métricas de % merma por material y por proyecto.

Trazabilidad

Lotes/series opcionales (vidrio, herrajes críticos).

Auditoría: quién movió, cuándo y por qué.

Compras y reabasto

Sugerencias de compra (MRP ligero) por mínimos, reservas y OTs próximas.

Integración con Órdenes de Compra y recepción.

Inventario físico

Conteos cíclicos y generales.

Discrepancias y ajustes con bitácora.

🧾 Datos principales

items: id, código, descripción, unidad, familia, foto, min/max, activo.

item_costs: item_id, fecha, tipo (estándar/último/CPP), valor, proveedor_id.

warehouses: id, nombre, dirección, responsable.

locations: id, warehouse_id, código ubicación.

stocks: item_id, warehouse_id, location_id, cantidad, reservado.

stock_moves: id, item_id, tipo (entrada/salida/traspaso/ajuste), cantidad, costo, doc_ref, motivo, fecha, usuario.

batches/serials (opcional): batch_id, item_id, vencimiento, tracking.

replenishment_rules: item_id, min, max, proveedor_pref, lote_compra.

waste: item_id, tipo_merma, cantidad, ot_id, fecha, nota.

🔁 Flujos

Ingreso por compra → recepción (con o sin lote) → actualización de costos/CPP → alta en ubicaciones.

Reserva por OT → bloqueo de cantidad → picking → salida real → registro de merma.

Traspaso (almacén A → B) con folio y confirmación.

Conteo → diferencias → ajuste con autorización.

Sugerencia de compra → orden de compra → recepción.

📌 Estados

Item: Activo / Inactivo / Obsoleto.

Movimiento: Borrador / Confirmado / Revertido.

Conteo: Programado / En proceso / Cerrado.

🔗 Integraciones

Cotizaciones: consulta de costos y disponibilidad; reserva al convertir.

Diseño/Producción: BOM y lista de corte; consumos reales por OT.

Compras: OCs, recepción y actualización de costos.

Finanzas: valorización de inventario (CPP), reportes de costo.

Reportes: exportes CSV/PDF para auditoría y valuación.

🧠 Validaciones

No permitir salida sin stock disponible (o permitir backorder según política).

Lotes/series obligatorios si el item lo requiere.

Ajustes y revalorizaciones con doble autorización.

Traspaso requiere confirmación en almacén destino.

📈 KPIs

Rotación por familia/material.

Exactitud de inventario (% diferencia conteos).

Cobertura (días de inventario) por ítem.

Merma (%) por material y por OT.

Cumplimiento de reabasto (OTIF de compras).

Varianza Costo teórico vs real por proyecto.

🔐 Permisos

Almacén: registrar entradas/salidas, traspasos, conteos.

Compras: crear OC, recibir y actualizar costos.

Producción: reservar y consumir por OT.

Auditor: leer todo, sin editar.

Administrador: políticas, revalorizaciones, catálogos.

🧩 Funciones avanzadas (Pro)

MRP ligero: proyecciones por calendario de OTs y lead time de proveedores.

Nesting integrado: optimización de corte y merma esperada → consumo teórico.

Etiquetado (QR) por ubicación/lote para escaneo móvil.

Multi-moneda y costos indexados por tipo de cambio.

Dashboards operativos en tiempo real (reservas, faltantes críticos).

Políticas de FEFO/ FIFO por familia.