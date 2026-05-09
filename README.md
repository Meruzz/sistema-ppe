# Sistema PPE

Sistema de gestión del **Programa de Participación Estudiantil** (PPE) para unidades educativas de bachillerato en Ecuador. Permite registrar y certificar las 80 horas obligatorias de participación estudiantil por alumno.

---

## Tecnologías

| Capa | Tecnología |
|------|------------|
| Framework | Laravel 13 |
| Auth | Laravel Breeze |
| Roles | spatie/laravel-permission |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js |
| Build | Vite |
| PDF | barryvdh/laravel-dompdf |
| Base de datos | MySQL |
| PHP | 8.3 |

---

## Funcionalidades

- **3 roles**: administrador, docente, alumno — cada uno con su propio dashboard
- **Gestión de alumnos**: registro, seguimiento de horas completadas, barra de progreso
- **Gestión de docentes y materias**
- **Grupos y actividades**: registro de asistencia por actividad
- **Reportes PDF**: certificado por alumno y reporte por grupo
- **Dark / light mode**: toggle persistente en localStorage
- **Diálogos de confirmación personalizados**: modal estilizado con Alpine.js (no `confirm()` nativo)

---

## Instalación local

### Requisitos

- PHP 8.3+
- Composer
- Node.js + npm
- MySQL

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/<tu-usuario>/sistema-ppe.git
cd sistema-ppe

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env
# DB_DATABASE=sistema_ppe
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Ejecutar migraciones y seeders (datos de demo incluidos)
php artisan migrate --seed

# 7. Compilar assets
npm run build

# 8. Iniciar servidor de desarrollo
php artisan serve
```

---

## Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | `admin@ppe.edu.ec` | `password` |
| Docente | `docente1@ppe.edu.ec` … `docente5@ppe.edu.ec` | `password` |
| Alumno | `alumno1@ppe.edu.ec` … `alumno30@ppe.edu.ec` | `password` |

---

## Estructura de la base de datos

```
users
alumnos          → alumno_grupo (pivot) → grupos
docentes
materias
grupos           → actividades → alumno_actividad (asistencia)
```

---

## Comandos útiles

```bash
# Resetear DB con datos de demo
php artisan migrate:fresh --seed

# Compilar en modo desarrollo (watch)
npm run dev

# Ver todas las rutas registradas
php artisan route:list

# Limpiar caché
php artisan view:clear && php artisan config:clear && php artisan cache:clear
```

---

## Capturas de pantalla

> *Próximamente*

---

## Licencia

MIT
