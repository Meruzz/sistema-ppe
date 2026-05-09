# HANDOFF — Sistema PPE

> **Para Claude (próxima sesión):** Lee este archivo completo antes de hacer cualquier cosa. Te da el contexto exacto de dónde quedamos.
> **Para Brandon:** En la nueva sesión di simplemente *"lee HANDOFF.md y continuamos"*.

---

## 1. Qué es este proyecto

Modernización de **SistemaPPE** — un CRM educativo para gestionar el **Programa de Participación Estudiantil** (80 horas obligatorias en bachillerato, Ecuador). El sistema rastrea horas, asistencia y certifica a los alumnos.

- **Repo original (referencia, no se toca):** https://github.com/Meruzz/SistemaPPE — PHP custom "Bee Framework" + MySQL, abandonado.
- **Este proyecto:** Reescritura completa en Laravel 11 (en realidad 13 al instalar). Mantiene la lógica de negocio, modernizado 10x.
- **Destino:** Portfolio personal + posible uso real en una unidad educativa.
- **Owner:** Brandon Almachi (graduado del ITSI).

---

## 2. Stack técnico actual

| Capa | Tecnología |
|------|------------|
| Framework | Laravel 13.8.0 |
| Auth | Laravel Breeze (Blade) |
| Roles | spatie/laravel-permission (3 roles: `administrador`, `docente`, `alumno`) |
| ORM | Eloquent + Migrations + Seeders |
| Frontend | Blade + Tailwind CSS 3 + Alpine.js (incluido por Breeze) |
| Build | Vite |
| PDF | barryvdh/laravel-dompdf |
| DB | MySQL (vía Laragon) |
| Server local | Laragon → `http://sistema-ppe.test` |
| PHP | 8.3.30 |

**Path absoluto del proyecto:** `C:\laragon\www\sistema-ppe`

---

## 3. Entorno local (Windows + Laragon)

- **Laragon Full** instalado en `C:\laragon\` — incluye PHP 8.3, MySQL, Composer, Apache.
- Para arrancar: abrir Laragon → **Start All**.
- Terminal de trabajo: PowerShell 7 o la terminal interna de Laragon.
- Comandos globales disponibles: `php`, `composer`, `npm`, `node`, `bun`, `uv`.

---

## 4. Plugins y skills instalados en Claude Code

### claude-mem (plugin)
- **Qué hace:** captura cada sesión, comprime con IA, reinyecta contexto en sesiones futuras.
- **Estado:** ✅ Instalado y funcional. Worker activo en `http://localhost:37777`.
- **Scope:** user (`~/.claude/plugins/marketplaces/thedotmack/`)
- **Datos en:** `C:\Users\BRANDON\.claude-mem\` (DB SQLite + Chroma vectors)
- **Dependencias instaladas:** Bun 1.3.13, uv 0.11.12.

### web-design-guidelines (skill)
- **Qué hace:** audita UI contra 100+ reglas de accesibilidad/UX de Vercel Labs.
- **Trigger:** pedir "review my UI" / "audit design" / "check accessibility".

### find-skills (skill)
- **Qué hace:** ayuda a descubrir más skills útiles según el contexto del trabajo.

---

## 5. Estado actual del proyecto

### Infraestructura y lógica ✅

- **Setup completo**: Laravel + Breeze + Spatie + dompdf instalados, migrations + seeders corriendo.
- **Esquema BD**: 9 tablas (users, alumnos, docentes, materias, grupos, alumno_grupo, actividades, alumno_actividad, permission_*).
- **Modelos Eloquent**: Alumno, Docente, Materia, Grupo, Actividad, User — todos con relaciones y accessors (ej: `nombre_completo`, `horas_completadas`, `progreso_horas`).
- **Form Requests**: AlumnoRequest, DocenteRequest, GrupoRequest, MateriaRequest, ActividadRequest.
- **Controllers**: DashboardController (vista por rol), AlumnoController, DocenteController, GrupoController, MateriaController, ActividadController, ReporteController (PDF).
- **Rutas**: web.php con middleware de roles.
- **Vistas**: 3 dashboards diferenciados, CRUDs completos para los 5 módulos, reportes PDF.
- **Seeders demo**: 1 admin + 5 docentes + 30 alumnos + 5 grupos + 4 materias + 15 actividades con asistencias.
- **Auth + Roles funcionando**: los 3 roles entran y ven su dashboard correspondiente.
- **Bug crítico arreglado**: route parameter `actividade` → `actividad` (Spanish pluralization fix) usando `->parameters(['actividades' => 'actividad'])`.

### Refactor de diseño ✅ COMPLETO

**Diseño elegido:** **"Sober Pro"** — Stripe/Linear/Vercel style, paleta slate + brand blue (#2563eb), Inter font, esquinas rounded-lg, sombras suaves, mucho whitespace. Mantiene dark/light toggle.

**Todos los archivos migrados:**

- ✅ `tailwind.config.js` — paleta brand-* con shade 950 añadido
- ✅ `resources/css/app.css` — clases custom (cy-card, cy-btn-*, cy-input, cy-badge-*, cy-progress, cy-stat, cy-page-title, cy-page-subtitle)
- ✅ `resources/views/layouts/` — app, guest, navigation
- ✅ `resources/views/components/` — todos los componentes Breeze
- ✅ `resources/views/auth/login.blade.php`
- ✅ `resources/views/dashboard/` — admin, docente, alumno
- ✅ `resources/views/alumnos/` — index, show, _form
- ✅ `resources/views/docentes/` — index, show, _form
- ✅ `resources/views/materias/` — index, _form
- ✅ `resources/views/grupos/` — index, show, _form
- ✅ `resources/views/actividades/` — index, show, _form
- ✅ Build producción exitoso (CSS 69.68 kB, JS 45.25 kB)

### Modal de confirmación personalizado ✅ COMPLETO

Reemplazamos todos los `confirm()` nativos del navegador (que rompían el diseño) por un modal estilizado con Alpine.js.

**Archivos involucrados:**
- ✅ `resources/views/components/confirm-modal.blade.php` — componente del modal
- ✅ `resources/views/layouts/app.blade.php` — modal incluido globalmente + `x-data` en `<main>` (necesario para que Alpine.js escuche los eventos de los botones de toda la app)
- ✅ Botones de eliminar en: `alumnos/index`, `docentes/index`, `grupos/index`, `materias/index` — todos usan `@click="$dispatch('confirm-delete', {...})"` en lugar de `onclick="confirm()"`

**Fix Alpine.js clave:** el `<main>` en `app.blade.php` debe tener `x-data` para que el reactive scope de Alpine cubra todos los botones. Sin esto, `$dispatch` no funciona.

### Limpieza de archivos ✅

- ✅ Eliminado `resources/views/dashboard.blade.php` — dashboard Breeze por defecto, nunca se usó
- ✅ Eliminado `resources/views/welcome.blade.php` — página welcome de Laravel, nunca se muestra (ruta `/` redirige a dashboard)
- ✅ `README.md` reescrito con documentación propia del proyecto (apto para GitHub)

---

## 6. Credenciales de prueba

| Rol | Email | Password |
|-----|-------|----------|
| Admin | `admin@ppe.edu.ec` | `password` |
| Docente (1-5) | `docente1@ppe.edu.ec` … `docente5@ppe.edu.ec` | `password` |
| Alumno (1-30) | `alumno1@ppe.edu.ec` … `alumno30@ppe.edu.ec` | `password` |

---

## 7. Comandos útiles

```bash
# Reset DB completa con datos demo
php artisan migrate:fresh --seed

# Solo seeders
php artisan db:seed

# Recompilar frontend (desarrollo, watch)
npm run dev

# Recompilar frontend (producción)
npm run build

# Ver todas las rutas
php artisan route:list

# Limpiar caché si algo se ve raro
php artisan view:clear && php artisan config:clear && php artisan cache:clear
```

---

## 8. Decisiones de diseño relevantes

- **Idioma de la app:** español (`APP_LOCALE=es`).
- **Paleta brand:** azul `#2563eb` (Tailwind brand-600). Verde para "completado/positivo", rojo para destructivo, amber para "pendiente".
- **Tipografía:** Inter (sans-serif legible).
- **Esquinas:** `rounded-md` / `rounded-lg`.
- **Sombras:** custom `shadow-soft`, `shadow-soft-md`, `shadow-soft-lg` (muy sutiles).
- **Densidad:** generosa, mucho whitespace, no apretado.
- **Dark mode:** sí, vía toggle en navbar (persistente en localStorage, anti-FOUC con script inline en `<head>`).
- **Accesibilidad:** reglas de la skill `web-design-guidelines` aplicadas (focus-visible ring, aria-labels, semantic HTML, autocomplete correcto en inputs, prefers-reduced-motion respetado, theme-color meta, color-scheme, etc.).
- **Confirmaciones destructivas:** modal Alpine.js en lugar de `confirm()` nativo para mantener el diseño consistente.

---

## 9. Próximas iteraciones

1. **Estilizar vistas de perfil** (`/profile`) — aún usan el estilo Breeze por defecto, pendiente migrar a Sober Pro.
2. **Tests** con PHPUnit/Pest — al menos auth y módulo de horas.
3. **Importación masiva CSV** de alumnos.
4. **Notificaciones por email** cuando un alumno llega al 50%, 80%, 100%.
5. **Deploy** — Railway.app o Forge (gratis para portfolio).
6. **Capturas de pantalla** para el README.

---

## 10. Cómo arrancar la próxima sesión (VS Code + Claude Code extension)

### Paso 1 — Abrir el proyecto en VS Code

1. Abrir **Visual Studio Code**.
2. Si aún no tienes la extensión: ir a **Extensions** (Ctrl+Shift+X) → buscar **"Claude Code"** (publisher Anthropic) → Install.
3. Abrir el proyecto: **File > Open Folder...** → `C:\laragon\www\sistema-ppe` → trust folder.

### Paso 2 — Iniciar Claude Code

- Abre el panel de Claude Code desde la sidebar (icono de Anthropic) o con el atajo de teclado.
- En la primera apertura del proyecto, la extensión disparará el **SessionStart** hook → claude-mem activa la captura para esta sesión.

### Paso 3 — Verificar que claude-mem está activo

```
/plugin list
```

Si no aparece o muestra error:

```
/reload-plugins
```

### Paso 4 — Retomar el trabajo

Como primer prompt:

> *"Lee HANDOFF.md y continuamos donde dejamos."*

### Paso 5 — Viewer de claude-mem (opcional)

Abre `http://localhost:37777` para ver el live feed de observaciones capturadas.

---

## Notas para subir a GitHub

- El `.gitignore` ya excluye `.env`, `vendor/`, `node_modules/`, `public/build/` y `storage/*.key` — seguro para subir tal cual.
- El `README.md` ya tiene instrucciones de instalación limpias para quien clone el repo.
- Las credenciales de prueba (`password`) están en los seeders — es intencional para el entorno demo, no son datos reales.
- El `HANDOFF.md` puede incluirse en el repo; es documentación interna del proceso de desarrollo.

---

**Última actualización:** Modal de confirmación ✅ · Vistas Breeze no usadas eliminadas ✅ · README reescrito para GitHub ✅ · Pendiente: estilizar `/profile` + tests + deploy.
