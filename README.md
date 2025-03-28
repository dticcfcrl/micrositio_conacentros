# CONACENTROS - Sistema Web

**Versión:** 1.0  
**Fecha de Documentación:** Ago/2024  
**Autor:** Meridiano99

## Descripción del Proyecto

Micrositio CONACENTROS es una plataforma web diseñada para facilitar el acceso a información y servicios para trabajadores y empleadores en el contexto de la conciliación laboral. El sistema incluye herramientas como un chatbot, sistema de reservas de citas, calculadora de indemnizaciones, y módulos de capacitación para mejorar la eficiencia en la resolución de conflictos laborales.

## Funcionalidades Principales

1. **Landing Page:** Proporciona una visión general de los servicios ofrecidos y noticias relevantes.
2. **Chatbot:** Asistente virtual que responde preguntas frecuentes y guía a los usuarios.
3. **Sistema de Reserva de Citas:** Permite agendar citas en los Centros de Conciliación Laboral (CCL).
4. **Sistema de Administración de Correos:** Envío de notificaciones automáticas a usuarios.
5. **Calculadora de Indemnizaciones:** Herramienta para estimar indemnizaciones por despido.
6. **Geolocalización de CCLs:** Muestra los centros de conciliación más cercanos.
7. **CMS (Statamic):** Sistema de gestión de contenido para administradores del sistema.

## Tecnologías Utilizadas

- **Laravel**: Framework para el desarrollo del backend.
- **Statamic**: Sistema de gestión de contenido (CMS) basado en archivos.
- **BotMan**: Framework para el desarrollo de chatbots en PHP.
- **MySQL**: Base de datos para almacenar y gestionar la información.
- **Power BI**: Herramienta para visualización de estadísticas y análisis de datos.

## Instalación

1. Clona el repositorio:

   ```bash
   git clone https://github.com/tu-usuario/micrositio_conacentros.git
   ```

2. Accede al directorio del proyecto:

   ```bash
   cd micrositio_conacentros
   ```

3. Instala las dependencias:

   ```bash
   composer install
   ```

4. Configura las variables de entorno `.env`:

   ```bash
   cp .env.example .env
   ```

   Asegúrate de configurar correctamente la base de datos y otras variables en el archivo `.env`.

5. Inicia el servidor local:

   ```bash
   php artisan serve
   ```

## Uso

- Accede a la página principal para explorar las funcionalidades disponibles.
- Utiliza el chatbot para obtener respuestas rápidas a preguntas frecuentes.
- Agenda citas en los CCLs utilizando el sistema de reserva.
- Utiliza la calculadora para estimar indemnizaciones.

## Contribuciones

Las contribuciones son bienvenidas. Por favor, sigue los siguientes pasos para colaborar:

1. Haz un fork del repositorio (Opcional).
2. Crea una rama para tu nueva funcionalidad (`git checkout -b nueva-funcionalidad`).
3. Haz commit de tus cambios (`git commit -m 'Añadir nueva funcionalidad'`).
4. Haz push a la rama (`git push origin nueva-funcionalidad`).
5. Crea un pull request.