# practica-iaw-1.9
#Arquitectura de una aplicación web LAMP en dos niveles
## 1. Introducción
Este proyecto tiene como objetivo crear una arquitectura en dos niveles para una pila LAMP, configurando los servicios de manera automatizada utilizando scripts de Bash. Los pasos incluyen:

- Configuración del servidor web con Apache y PHP. <ins>(*Frontend*)</ins> 
- Configuración del servidor MySQL con soporte para conexiones remotas. <ins>(*Backend*)</ins> 
- Integración de una aplicación web que se conecta al servidor de base de datos remoto. <ins>(*Wordpress*)</ins>

[Usuario] --> [Servidor Apache/PHP] --> [Servidor MySQL]

Para ello desarrollaremos varios scripts que dividiremos entre los que tenemos que instalar en la maquina que actuará con Frontend y otros que instalaremos en la maquina que actuará como Backend.

## 2. Creación de una instancia EC2 en AWS

Para empezar a crear nuestras maquinas deberemos primero crear dos grupos de seguridad diferenciados
-El grupo de seguidad para el <ins>*Frontend*</ins> , que llamaremos por ejemplo <ins>*gs_Frontend*</ins> , con las siguientes reglas de entrada: 

  ![nEXNnoDtX0](https://github.com/user-attachments/assets/6c9b5957-657f-4546-bcca-74f3a7a5163d)


-El grupo de seguidad para el <ins>*Backend*</ins> , que llamaremos por ejemplo <ins>*gs_Backend*</ins> , con las siguientes reglas de entrada: 

  ![rgdoo0bIyy](https://github.com/user-attachments/assets/9af7db71-59ee-45eb-8605-5496bb20d09c)

A continuacion, lanzaremos las instancias y seleccionaremos para cada una el grupo de seguridad que hemos creado para ellas.

Tambien crearemos y asociaremos dos IPs elásticas a cada una de ellas.

- la IP de la maquina <ins>*Frontend*</ins> es la siguiente

  ![TWXoA6P5Op](https://github.com/user-attachments/assets/a5aec8b3-bd36-4085-9615-9babb266c538)

- la IP de la maquina <ins>*Backend*</ins> es la siguiente

  ![zHjLAoEvzB](https://github.com/user-attachments/assets/fcf52f0f-20a1-402c-98ba-4de8ff2c6747)


## 3. Instalación de pila LAMP en Backend.

El script para el *Backend* irá enfocada a la instalación y configuración de <ins>MySQL</ins>, seguiremos el mismo esquema que hemos seguido anteriormente [Pactica 1.1 script install LAMP](https://github.com/marinaferb92/practica-iaw-1.1/blob/03508db12ab4537559efa67ba80acf9b137da50e/scripts/install_lamp.sh) 

El unico cambio que haremos será el de añadir una linea que configure el archivo `/etc/mysql/mysql.conf.d/mysqld.cnf` para cambiar la directiva de configuración 

````
[mysqld]
bind-address = 127.0.0.1
````

por la IP privada de nuestra maquina de Frontend, asegurando que el unico servidor que pueda conectarse a nuestra base de datos sea este. 

Para ello utilizaremos el comando *sed* 

`sed -i "s/127.0.0.1/$MYSQL_PRIVATE_IP/" /etc/mysql/mysql.conf.d/mysqld.cnf`

La variable *$MYSQL_PRIVATE_IP* estará definida dentro del archivo .env 

<ins>[SCRIPT install_lamp_backend.sh](https://github.com/marinaferb92/practica-iaw-1.9/blob/4a77fa3e6f3dafd380c8ef5e70cad00e2a2e3023/scripts/install_lamp_backend.sh) </ins>

Podemos comprobar que podemos acceder a Mysql desde esta máquina

  ![BNUQM1TTRL](https://github.com/user-attachments/assets/44569e9e-7024-457d-81be-927975f35960)


## 4. Instalación de pila LAMP en Frontend.
El script para el *Frontend* irá enfocada a la instalación y configuración de <ins>Apache con PHP</ins> en el servidor, seguiremos el mismo esquema que hemos seguido anteriormente [Pactica 1.1 script install LAMP](https://github.com/marinaferb92/practica-iaw-1.1/blob/03508db12ab4537559efa67ba80acf9b137da50e/scripts/install_lamp.sh) 

Pero deberemos omitir todos los pasos relativos a la instalación de MySQL, ya que este servidor se conectará al Backend para acceder a la base de datos

<ins>[SCRIPT install_lamp_frontend.sh](https://github.com/marinaferb92/practica-iaw-1.9/blob/1fc251435079787e491f9fb4e09cf44661404c1e/scripts/install_lamp_frontend.sh) </ins>

Podemos comprobar la instalación de Apache y que este se está ejecutando bien.

  ![y9qyWWmqIP](https://github.com/user-attachments/assets/d08b4cd5-93d2-471a-b183-d44209b13911)

Podemos comprobar tambien que las maquinas pueden comunicarse entre si haciendo un ping de una a otra

  ![WE6MMfYTtz](https://github.com/user-attachments/assets/1a0cc076-94ca-4cf8-a37e-4696c02e8a7a)


## 5. Registrar un Nombre de Dominio

Usamos un proveedor gratuito de nombres de dominio como son Freenom o No-IP.
En nuestro caso lo hemos hecho a traves de No-IP, nos hemos registrado en la página web y hemos registrado un nombre de dominio con la IP pública del servidor.

En el registro del dominio debemos poner la IP publica de la <ins>máquina Frontend</ins> que sera donde instalaremos Wordpress

   ![JUGvuKsF0V](https://github.com/user-attachments/assets/1315802e-f516-423a-b6fd-dc07ae6e5ca6)



## 6. Instalar Certbot y Configurar el Certificado SSL/TLS con Let’s Encrypt
Para la realizacion de este apartado seguiremos los pasos detallados en la practica-iaw-1.5 y utilizaremos el script ``` setup_letsencrypt_certificate.sh ```.

- Este script se ejecutará en la <ins>máquina Frontend</ins> 

[Practica-iaw-1.5](https://github.com/marinaferb92/practica-iaw-1.5)

[Script setup_letsencrypt_certificate.sh](scripts/setup_letsencrypt_certificate.sh)



## 7. Configuracion de la base de datos de Wordpress en el Backend
El siguiente paso para seguir con el despliegue de Wordpress sería el de configurar la base de datos en MySQL, 
para ello ejecutaremos el siguiente script.

[deploy_wordpress_backend.sh](https://github.com/marinaferb92/practica-iaw-1.9/blob/47a5b265793e666a92b7484241ab0d5106d39fc4/scripts/deploy_wordpress_backend.sh)

En el encontramos las siguientes lineas
````
mysql -u root <<< "DROP DATABASE IF EXISTS $WORDPRESS_DB_NAME"
mysql -u root <<< "CREATE DATABASE $WORDPRESS_DB_NAME"
mysql -u root <<< "DROP USER IF EXISTS $WORDPRESS_DB_USER@$FRONTEND_PRIVATE_IP"
mysql -u root <<< "CREATE USER $WORDPRESS_DB_USER@$FRONTEND_PRIVATE_IP IDENTIFIED BY '$WORDPRESS_DB_PASSWORD'"
mysql -u root <<< "GRANT ALL PRIVILEGES ON $WORDPRESS_DB_NAME.* TO $WORDPRESS_DB_USER@$FRONTEND_PRIVATE_IP"
````
Con ellas realizamos las siguientes acciones:
1. Eliminar la base de datos si ya existe.
2. Crear la base de datos definida en la variable $WORDPRESS_DB_NAME.
3. Eliminar el usuario definido en $WORDPRESS_DB_USER que se conecta desde la IP $FRONTEND_PRIVATE_IP.
4. Crear este usuario.
5. Darle todos los provilegios sobre la base de datos.

Para que esto funcione correctamente deberemos configurar las siguientes variables en el archivo `.env`
- WORDPRESS_DB_NAME = nombre de la base de datos
- WORDPRESS_DB_USER = nombre del usuario 
- FRONTEND_PRIVATE_IP = IP privada de la maquina Frontend
- WORDPRESS_DB_PASSWORD = contraseña del usuario de la base de datos



## 8. Configuracion Wordpress en el Frontend
El siguiente paso para seguir con el despliegue de Wordpress sería el de configurar Instalar y configurar Wordpress en el servidor Frontend.
El script que utilizaremos estará diseñado para automatizar la instalación y la configuración de *Wordpress*.

Utilizaremos la misma estructura que en el `deploy_wordpress_with_wpcli.sh`de la [practica 1.7](https://github.com/marinaferb92/practica-iaw-1.7/blob/8a96bd92c27c430d7d369159c106e460c37e0053/scripts/deploy_wordpress_with_wpcli.sh)

Aunque deberemos eliminar la parte de la creacion de la base de datos que ya hemos configurado en el  
[deploy_wordpress_backend.sh](https://github.com/marinaferb92/practica-iaw-1.9/blob/389b6e81c313e8916e1c3edefef1b3bbf349c50c/scripts/deploy_wordpress_backend.sh)

En la creacion del archivo de configuración de WordPress deberemos cambiar la variable `$WORDPRESS_DB_HOST` por `$WORDPRESS_DB_PASSWORD` que introduciremos en el archivo `.env` y le pondremos como valor la IP privada de la maquina Backend. 

````
wp config create \
  --dbname=$WORDPRESS_DB_NAME \
  --dbuser=$WORDPRESS_DB_USER \
  --dbpass=$WORDPRESS_DB_PASSWORD \ 
  --dbhost=$BACKEND_PRIVATE_IP \
  --path=$WORDPRESS_DIRECTORY \
  --allow-root
````

Una vez hechos los cambios ejecutaremos el script [deploy_wordpress_frontend.sh](https://github.com/marinaferb92/practica-iaw-1.9/blob/9586848899665226cdddb1822f3984e8297ac0c6/scripts/deploy_wordpress_frontend.sh)


# 9. Comprobaciones

Tras la ejecución de todos los scripts en las maquinas correctas podremos meternos en el nombre de dominio que habiamos registrado y podremos poner en uso Wordpress

  ![GWINR412sf](https://github.com/user-attachments/assets/1b8cc518-1a70-493f-ae85-5b2e8f258f69)

  ![qhXRxMHz05](https://github.com/user-attachments/assets/c8ddcea2-75a2-4e93-9838-5fcff34610e5)

Podemos tambien verificar que la instalación del certificado ha sido exitosa

  ![zzYX9Eihf7](https://github.com/user-attachments/assets/ffd5ff1e-d9d3-4587-9f3d-f536fb828a38)

Aunque que *Wordpress* funcione perfectamente, implica que el servidor *Frontend* esta comunicandose con el servidor *Backend* y puede acceder a su base de datos. Si queremos estar seguros de que la maquina front end se comunica con el backend, podemos instalar en esta MySQL y intentar entrar en MySQL desde esta.

  ![14oRXB4B5i](https://github.com/user-attachments/assets/60f914c0-79c5-4016-a046-00b0aba20196)

















