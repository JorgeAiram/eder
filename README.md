# Sistema Backend en Laravel 11 con Docker y CI/CD en GitHub Actions

Este proyecto implementa una arquitectura backend contenerizada con **Docker**, **Nginx** y **MySQL 8**, junto con un ciclo automatizado de Integración Continua (CI) y Despliegue Continuo (CD) mediante **GitHub Actions** y **GitHub Container Registry (GHCR)**.

---

## 1. Arquitectura y Componentes

- **`app` (Laravel 11 / PHP 8.2-FPM):** Contenedor de la API backend.
- **`nginx` (Nginx Alpine):** Servidor web y proxy inverso hacia PHP-FPM en puerto `9000`.
- **`database` (MySQL 8.0):** Base de datos relacional con volumen persistente nombrado `mysql_data`.
- **`ghcr.io`:** Registro de contenedores de GitHub para distribución de imágenes inmutables.
- **`GitHub Actions`:** Orquestador de pruebas (Pint + PHPUnit) y despliegue automático con verificación de salud y rollback.

---

## 2. Puesta en Marcha Local en un Solo Comando

Para levantar el entorno completo localmente sin configuraciones complejas:

```bash
# 1. Clonar el repositorio
git clone https://github.com/JorgeAiram/eder.git
cd eder

# 2. Copiar variables de entorno base
cp .env.example .env

# 3. Iniciar todos los servicios con Docker Compose
docker compose up -d

# 4. Generar clave de aplicación y ejecutar migraciones
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --force
```

Acceso al servicio:
- **API Base:** `http://localhost:8080/`
- **Chequeo de Salud:** `http://localhost:8080/health`
- **CRUD de Productos:** `http://localhost:8080/api/products`

---

## 3. Pruebas y Calidad de Código

```bash
# Ejecutar pruebas unitarias y de integración
docker compose exec app php artisan test

# Validar formato y calidad de código con Laravel Pint
docker compose exec app vendor/bin/pint --test
```

---

## 4. Pipeline de CI/CD (GitHub Actions)

1. **Pull Request a `main` (CI):**
   - Ejecuta **Laravel Pint** para verificar estándares de código.
   - Ejecuta **PHPUnit** sobre una base de datos MySQL efímera.
   - Si alguna prueba falla, bloquea el merge al branch principal.

2. **Merge a `main` (Build & Publish):**
   - Construye la imagen Docker de producción (`Dockerfile`).
   - La etiqueta con el hash del commit (`${{ github.sha }}`) y con `latest`.
   - Publica la imagen en `ghcr.io/jorgeairam/eder`.

3. **Despliegue a Staging (CD):**
   - Genera un respaldo automático de base de datos (`mysqldump`) previo.
   - Descarga la imagen construida (`docker compose pull`).
   - Aplica migraciones (`php artisan migrate --force`).
   - Actualiza contenedores (`docker compose up -d`).
   - Consulta el endpoint `/health` con reintentos.
   - **Rollback:** Si la verificación falla, revierte de inmediato a la versión anterior y restaura la base de datos.
