# 🖥️ ERP/POS — Sistema de Gestión para Tienda de Laptops

Sistema ERP/POS desarrollado en Laravel para la gestión integral de una tienda de laptops: control de inventario por número de serie, ventas con carrito dinámico, facturación en PDF, reportes en Excel y dashboard con indicadores clave.

> Proyecto desarrollado como práctica intensiva para aplicar a posiciones Junior Backend Developer.

---

## 📸 Capturas

### Dashboard con KPIs y reportes
![Dashboard](docs/screenshots/dashboard.png)

### Venta con carrito AJAX
![Venta](docs/screenshots/venta-carrito.png)

### Gestión de productos
![Productos](docs/screenshots/productos.png)

### Login
![Login](docs/screenshots/login.png)

### Comprobante PDF
![PDF](docs/screenshots/pdf-venta.png)

### Exportación a Excel
![Excel](docs/screenshots/reporte-excel.png)

### API REST con respuesta JSON
![API](docs/screenshots/api-postman.png)

---

## 🚀 Funcionalidades

- 🔐 **Autenticación y roles** (admin / empleado) con middleware
- 📦 **Gestión de productos** con imágenes (upload, edición y borrado seguro de archivos)
- 🔢 **Inventario por número de serie** — cada laptop es una unidad única rastreable
- 🛒 **Ventas con carrito AJAX** — agregar/quitar productos sin recargar página
- 📄 **Comprobantes PDF** generados con DomPDF, numeración por tipo de voucher
- 📊 **Exportación a Excel** con Laravel Excel (formato auto-ajustado)
- ↩️ **Anulación de ventas** con devolución automática de stock vía transacciones DB
- 📈 **Dashboard interactivo** con KPIs, Top 5 productos, Top 3 clientes, últimas ventas y gráfica de los últimos 7 días (Chart.js)
- 👥 **Gestión de clientes** con validación de DNI / RUC / CE

---

## 🛠️ Stack Técnico

| Categoría | Tecnología |
|-----------|------------|
| Backend | PHP 8.2, Laravel 12.x |
| Frontend | Blade, Bootstrap 5, JavaScript (AJAX) |
| Base de datos | MySQL |
| Visualización | Chart.js |
| Reportes | DomPDF, Laravel Excel (Maatwebsite) |
| Autenticación | Laravel Auth + Middleware de roles |

---

## ⚙️ Instalación local

Requisitos: PHP 8.1+, Composer, MySQL, Node.js.

```bash
# 1. Clonar el repositorio
git clone https://github.com/elmercunya/laptop-store-erp.git
cd laptop-store-erp

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias frontend
npm install && npm run build

# 4. Configurar variables de entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env, luego:
php artisan migrate --seed

# 6. Crear enlace simbólico para imágenes
php artisan storage:link

# 7. Levantar el servidor
php artisan serve
```

Acceder en `http://localhost:8000`.

**Usuario de prueba:**
- Usuario: `admin`
- Password: `admin123`

---

## 🗂️ Arquitectura del proyecto

El sistema sigue el patrón MVC estándar de Laravel:

- **Models**: representan las entidades del negocio (Producto, Venta, Cliente, Unidad)
- **Controllers**: manejan la lógica de cada módulo (ProductoController, VentaController, etc.)
- **Migrations**: definen el esquema de BD versionado
- **Views (Blade)**: layout maestro con componentes reutilizables
- **Middleware**: control de acceso por rol

Aspectos técnicos destacables:
- **Transacciones DB** en anulación de ventas para garantizar integridad del stock
- **Eager Loading** con `with()` y `orWhereHas()` para evitar N+1 queries en buscadores
- **Manejo de archivos**: borrado del archivo físico anterior al actualizar la imagen de un producto
- **Numeración correlativa** de comprobantes por tipo de voucher (boleta/factura)

---

## 🔌 API REST

El sistema expone una API REST versionada para consumo externo.

### Endpoints disponibles

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/v1/products` | Lista productos con paginación y filtros |
| GET | `/api/v1/products/{id}` | Detalle de un producto |

### Parámetros de consulta (`/api/v1/products`)

| Parámetro | Tipo | Descripción |
|-----------|------|-------------|
| `search` | string | Búsqueda parcial por nombre |
| `category_id` | integer | Filtrar por categoría |
| `per_page` | integer | Resultados por página (máx. 50, default 10) |
| `page` | integer | Número de página |

### Ejemplo de respuesta

```json
{
  "success": true,
  "data": [
    {
      "id": 4,
      "image": "http://localhost:8000/storage/products/D1DvLdAspg82NPZfV2Zcb2082udyyNg4Oofi4N8O.png",
      "name": "Asus Vivobook 15",
      "category": {
        "id": 2,
        "name": "Laptops nueva"
      },
      "price": 2399,
      "date": "2026-05-04 16:48:34"
    },
    {
      "id": 5,
      "image": "http://localhost:8000/storage/products/mt3GHly233FYpyAaR4Ry40ojQsqMmgZtkisy7znt.png",
      "name": "Lenovo IdeaPad Slim 3 15IAH8",
      "category": {
        "id": 2,
        "name": "Laptops nueva"
      },
      "price": 2589,
      "date": "2026-05-04 17:01:47"
    }
  ],
  "meta": {
    "current_page": 2,
    "last_page": 2,
    "per_page": 2,
    "total": 4
  }
}
```

### Repuesta de error (404)

Cuando se solicita un producto que no existe:

**Petición**

GET api/v1/products/999

**Respuesta**

- Código HTTP: 404 Not Found
- Cuerpo Json:

```json
{
  "success": false,
  "message": "Producto no encontrado"
}
```

### Aspectos técnicos

- **API Resources** para transformación de respuestas y desacople del modelo de BD
- **Eager loading** (`with()`) para evitar el problema N+1
- **Paginación** con límite máximo para prevenir abusos
- **Versionado** con prefijo `/v1` para evolución sin romper clientes
- **Códigos HTTP semánticos** (200, 404)

---

## 📌 Roadmap

- [x] API REST básica con Resources y paginación
- [ ] Autenticación API con Sanctum
- [ ] Tests con PHPUnit
- [ ] Dockerización
- [ ] Despliegue en producción

---

## 👤 Autor

**Elmer Benjamin Cunya Quiroz** — Desarrollador Backend Junior
- 📧 elmer.cunyaq@gmail.com
- 💼 LinkedIn: Próximamente
- 🐙 GitHub: https://github.com/elmercunya
