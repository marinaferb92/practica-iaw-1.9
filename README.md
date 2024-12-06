# practica-iaw-1.9

## 1. Introducción
Este proyecto tiene como objetivo crear una arquitectura en dos niveles para una pila LAMP, configurando los servicios de manera automatizada utilizando scripts de Bash. Los pasos incluyen:

- Configuración del servidor web con Apache y PHP. (*Frontend*)
- Configuración del servidor MySQL con soporte para conexiones remotas. (*Backend*)
- Integración de una aplicación web que se conecta al servidor de base de datos remoto. (*Wordpress*)

Para ello desarrollaremos varios scripts que dividiremos entre los que tenemos que instalar en la maquina que actuará con Frontend y otros que instalaremos en la maquina que actuará como Backend.

## 2. Creación de una instancia EC2 en AWS

Para empezar a crear nuestras maquinas deberemos primero crear dos grupos de seguridad diferenciados
-El grupo de seguidad para el *Frontend*, que llamaremos por ejemplo *gs_Frontend*, con las siguientes reglas de entrada: 

  ![nEXNnoDtX0](https://github.com/user-attachments/assets/6c9b5957-657f-4546-bcca-74f3a7a5163d)


-El grupo de seguidad para el *Backend*, que llamaremos por ejemplo *gs_Backend*, con las siguientes reglas de entrada: 

  ![rgdoo0bIyy](https://github.com/user-attachments/assets/9af7db71-59ee-45eb-8605-5496bb20d09c)

A continuacion, lanzaremos las instancias y seleccionaremos para cada una el grupo de seguridad que hemos creado para ellas.

Tambien crearemos y asociaremos dos IPs elásticas a cada una de ellas.

la IP de la maquina *Frontend* es la siguiente

  ![TWXoA6P5Op](https://github.com/user-attachments/assets/a5aec8b3-bd36-4085-9615-9babb266c538)




















