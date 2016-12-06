--Comandos para postgresql dump de la base de datos 
pg_dump -Upostgres -W -hlocalhost -dmy_db_test -n encuestas > encuestas.sql
pg_dump -Uinnovaedu -W -h11.32.41.92 -dkio_prod2016 > kio_sied_2016.sql
--Ver base 
psql -U nameuser -h host -W solicitaPassword

--Enlistar bases de datos
\l
--Entrar a una base de datos
\c
--Ver tablas de la base de datos 
\d
--Ver tabla especifica 
\d nametable 
--cargar a una instancia
\i
--Dump de la base de datos
pg_dump -Upostgres -W -hlocalhost -dmy_db_test -n encuestas > encuestas.sql

--aplicar comandos  
postgres=# pg_ctl start
postgres=# pg_ctl status
postgres=# pg_ctl restart  --reinicia el postgres 
postgres=# pg_ctl start company
postgres=# pg_ctl status

----------- Configuración de permisos ruta donde se encuentre postgres C:\Program Files\PostgreSQL\9.5\data\pg_hba.conf
-- host    all             all              11.32.41.86/32            md5

-- Para salir de la visualización de una tabla, o cualquier actividad, sólo presionar la letra "q" 
 
C:\Users\Elizabeth>psql -Upostgres -W
Contraseña para usuario postgres:
psql (9.5.3)
         ^
postgres=# \i
\i: falta argumento requerido
postgres=# create database sied30_2016;   ---Crea una base de datos
CREATE DATABASE

postgres=# \c sied30_2016; --Entrar a la instancia de la base de datos creada  
Contraseña para usuario postgres:  --carga password de usuario
ADVERTENCIA: El código de página de la consola (850) difiere del código
            de página de Windows (1252).
            Los caracteres de 8 bits pueden funcionar incorrectamente.
            Vea la página de referencia de psql «Notes for Windows users»
            para obtener más detalles.
Ahora está conectado a la base de datos «sied30_2016» con el usuario «postgres».
sied30_2016=#     ---Ya esta dentro de la instancia o base de datos

--Posisionado en la ruta del archivo sql, carga "ddl y datos de la base " en sql ejem C:\Users\Elizabeth\name_file.sql
sied30_2016=#\i name_file.sql  


