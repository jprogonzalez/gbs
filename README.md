# TEST GLOBAL STANDAR

API para la gestión de usarios.

## 🚀 Requisitos

Asegúrate de tener instalado lo siguiente:

- PHP >= 8.1
- Composer
- Laravel >= 10
- MySQL o PostgreSQL

## 📦 Instalación

1. Clona el repositorio:

    ```bash
    git clone https://github.com/tu_usuario/tu_proyecto.git
    cd tu_proyecto
    ```

2. Instala las dependencias de PHP:

    ```bash
    composer install
    ```

3. Copia el archivo `.env.example` a `.env` y configura tus variables de entorno:

    ```bash
    cp .env.example .env
    ```

4. Genera la clave de la aplicación:

    ```bash
    php artisan key:generate
    ```

5. Configura tu base de datos en `.env`, luego ejecuta las migraciones y seeders:

    ```bash
    php artisan migrate --seed
    ```

6. corre el siguiente comando para configurar las llaves de passport:

    ```bash
    php artisan passport:install --force
    ```
7. consulta la documentación de la api realizada en POSTMAN <br>
<a href="https://documenter.getpostman.com/view/39886903/2sB2ca5erT#auth-info-fd7d7e8c-b1c9-4c74-858e-aeeb2cf43e9a" target="_blank">
    link de la documentación de postamn
</a>

## 👥 Usuarios de prueba

Durante el proceso de seed, se crean automáticamente dos usuarios para fines de prueba:

| Rol        | Nombre        | Email                  | Contraseña | role_id |
|------------|---------------|------------------------|------------|---------|
| Admin      | Super Admin   | superadmin@gmail.com   | secret     | 1       |
| Colaborador| Jhon Doe      | jhon@gmail.com         | secret     | 2       |

Puedes utilizar estos usuarios para probar el sistema de autenticación y los diferentes permisos por rol.


## 🧪 Pruebas

Este proyecto incluye un entorno de pruebas configurado con SQLite para facilitar la ejecución rápida de tests sin afectar la base de datos principal.

### ⚙️ Configuración

Se añadió un archivo `.env.testing` con la configuración necesaria para ejecutar los tests utilizando una base de datos en memoria (SQLite). Esto permite pruebas más rápidas y seguras.

Contenido clave de `.env.testing`:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
APP_ENV=testing
APP_KEY=base64:...
```

Para añadir la key en APP_KEY en .env.testing:

```bash
php artisan key:generate --env=testing
```

Para ejecutar las pruebas:

```bash
php artisan test
```
