# practica-iaw-1.9

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

El script para el Backend irá enfocada a la instalacíon y configuración de <ins>MySQL</ins>, seguiremos el mismo esquema que hemos seguido anteriormente [Pactica 1.1 script install LAMP](https://github.com/marinaferb92/practica-iaw-1.1/blob/03508db12ab4537559efa67ba80acf9b137da50e/scripts/install_lamp.sh) 

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

## 4. Instalación de pila LAMP en Backend.












