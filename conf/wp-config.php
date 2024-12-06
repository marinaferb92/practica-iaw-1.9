<?php

// Datos de conexión a la base de datos
define('DB_HOST', '172.31.18.110'); // IP del servidor MySQL (BACKEND_PRIVATE_IP)
define('DB_NAME', 'wordpress_db'); // Nombre de la base de datos (WORDPRESS_DB_NAME)
define('DB_USER', 'user');         // Usuario de la base de datos (WORDPRESS_DB_USER)
define('DB_PASSWORD', 'user');     // Contraseña del usuario (WORDPRESS_DB_PASSWORD)

// Conexión a la base de datos
$mysqli = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);