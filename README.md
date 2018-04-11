Creación de un CRUD para aprender Symfony
========================

Proyecto creado con la finalidad de familiarizarme con Symfony siguiendo las pautas 
ofrecidas a través de un curso proporcionado por la empresa Dapda.

Requisitos
--------------

  * PHP (yo he usado la versión 7.2).

  * Composer.
  
  * Una base de datos (yo he usado mariaDB).

  * Un servidor web (yo he usado Apache).

Como hacerlo funcionar
--------------

  * Clonar el repositorio en la carpeta pública de tu servidor web.
  
  * Importar el fichero **database.sql** en tu base de datos.

  * Dentro de la carpeta clonada ejecutar **composer install**, cuando pida los datos de configuración el nombre de la base de datos es **blog**, el usuario y contraseña el que tu tengas, lo demás por defecto.

  * Dar permisos de escritura y ejecución a los archivos que los pida. **sudo chmod 777 -R Symfony/**

Información
--------------

Por defecto los usuarios que se registran son todos administradores.
Son dos roles:
* ROLE_ADMIN
* ROLE_USER

Modificar en la base de datos o UserController.php para cambiar esto.