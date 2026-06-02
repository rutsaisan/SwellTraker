CREATE DATABASE IF NOT EXISTS swelltracker;

USE swelltracker;

-- =============================================================
--  MÓDULO: Usuarios y autenticación
-- =============================================================

-- -------------------------------------------------------------
-- TABLA: usuarios
-- Gestión de cuentas para el inicio de sesión multi-usuario.
-- -------------------------------------------------------------
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(80) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ultimo_login DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- TABLA: wavepilot_perfiles
-- Un perfil de juego por usuario.
-- -------------------------------------------------------------
CREATE TABLE wavepilot_perfiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL UNIQUE,
    personaje TINYINT NOT NULL DEFAULT 0,
    xp INT NOT NULL DEFAULT 0,
    nivel SMALLINT NOT NULL DEFAULT 1,
    xp_para_siguiente INT NOT NULL DEFAULT 500,
    sesiones_surf SMALLINT NOT NULL DEFAULT 0,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- TABLA: wavepilot_equipo
-- Equipo de surf por usuario (tabla, quillas, neopreno…).
-- -------------------------------------------------------------
CREATE TABLE wavepilot_equipo (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    clave VARCHAR(30) NOT NULL,
    valor VARCHAR(255) NOT NULL DEFAULT '',
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_clave (usuario_id, clave),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- TABLA: wavepilot_misiones_completadas
-- Registro de misiones que cada usuario ha completado.
-- -------------------------------------------------------------
CREATE TABLE wavepilot_misiones_completadas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    mision_id VARCHAR(10) NOT NULL,
    completada_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_mision (usuario_id, mision_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- TABLA: wavepilot_logros
-- Logros desbloqueados por cada usuario.
-- -------------------------------------------------------------
CREATE TABLE wavepilot_logros (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    logro_idx TINYINT UNSIGNED NOT NULL,
    desbloqueado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_logro (usuario_id, logro_idx),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
--  MÓDULO: SwellTracker (Olas / Condiciones)
-- =============================================================

-- -------------------------------------------------------------
-- TABLA: spots
-- Spots de surf guardados / favoritos.
-- -------------------------------------------------------------
CREATE TABLE spots (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    pais VARCHAR(80) NOT NULL,
    region VARCHAR(120) DEFAULT NULL,
    latitud DECIMAL(9,6) DEFAULT NULL,
    longitud DECIMAL(9,6) DEFAULT NULL,
    descripcion TEXT DEFAULT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- TABLA: spots_favoritos
-- Spots marcados como favoritos por cada usuario.
-- -------------------------------------------------------------
CREATE TABLE spots_favoritos (
    usuario_id INT UNSIGNED NOT NULL,
    spot_id INT UNSIGNED NOT NULL,
    añadido_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (usuario_id, spot_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (spot_id) REFERENCES spots(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
--  MÓDULO: Equipamiento
-- =============================================================

-- -------------------------------------------------------------
-- TABLA: equipamiento_guardado
-- Equipamiento personal guardado por el usuario.
-- -------------------------------------------------------------
CREATE TABLE equipamiento_guardado (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    categoria VARCHAR(60) NOT NULL,
    marca VARCHAR(80) DEFAULT NULL,
    modelo VARCHAR(120) DEFAULT NULL,
    notas TEXT DEFAULT NULL,
    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
--  MÓDULO: Tutoriales
-- =============================================================

-- -------------------------------------------------------------
-- TABLA: tutoriales_progreso
-- Progreso del usuario en los tutoriales de la app.
-- -------------------------------------------------------------
CREATE TABLE tutoriales_progreso (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    tutorial_id VARCHAR(60) NOT NULL,
    completado TINYINT(1) NOT NULL DEFAULT 0,
    porcentaje TINYINT UNSIGNED NOT NULL DEFAULT 0,
    actualizado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_usuario_tutorial (usuario_id, tutorial_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================================
--  DATOS INICIALES
-- =============================================================

-- Spots de surf de ejemplo
INSERT INTO spots (nombre, pais, region, latitud, longitud, descripcion) VALUES
('Pipeline',        'Hawaii, EEUU',  'North Shore Oahu',   21.6635,  -158.0518, 'El tubo más famoso del mundo'),
('Jeffreys Bay',    'Sudáfrica',     'Eastern Cape',      -34.0489,    26.8266, 'Ola derecha más perfecta de África'),
('Nazaré',          'Portugal',      'Centro',             39.6019,    -9.0710, 'Olas gigantes, récord mundial'),
('Hossegor',        'Francia',       'Nouvelle-Aquitaine', 43.6596,    -1.4350, 'Capital del surf europeo'),
('Mundaka',         'España',        'País Vasco',         43.4075,    -2.6988, 'La mejor ola izquierda de Europa'),
('Supertubes',      'Portugal',      'Alentejo',           37.6618,    -8.8191, 'Tubo rápido de arena'),
('Siargao Cloud 9', 'Filipinas',     'Mindanao',            9.8565,   126.0952, 'Reef break tropical perfecto'),
('Snapper Rocks',   'Australia',     'Gold Coast',        -28.1656,   153.5484, 'Inicio del Superbank');
('La Malvarrosa',    'España',             'Comunidad Valenciana',   39.4795,    -0.3228, 'Playa urbana clásica de fondo de arena'),
('La Patacona',      'España',             'Comunidad Valenciana',   39.4930,    -0.3235, 'Playa abierta al este de Valencia'),
('El Saler',         'España',             'Comunidad Valenciana',   39.3831,    -0.3245, 'Spot en entorno natural y dunas'),
('Teahupo''o',       'Polinesia Francesa', 'Tahiti',                -17.8471,  -149.2667, 'Ola de arrecife extremadamente pesada'),
('Uluwatu',          'Indonesia',          'Bali',                   -8.8149,   115.0884, 'Famoso reef break bajo un acantilado'),
('Zarautz',          'España',             'País Vasco',             43.2874,    -2.1699, 'Extensa playa con mucha consistencia'),
('Bells Beach',      'Australia',          'Victoria',              -38.3667,   144.2833, 'Point break histórico de derechas'),
('Mavericks',        'EEUU',               'California',             37.4925,  -122.4981, 'Peligrosa ola gigante de aguas frías'),
('Trestles',         'EEUU',               'California',             33.3853,  -117.5939, 'Point break perfecto de alto rendimiento'),
('Puerto Escondido', 'México',             'Oaxaca',                 15.8653,   -97.0681, 'El Pipeline mexicano, beach break pesado'),
('Somo',             'España',             'Cantabria',              43.4542,    -3.7381, 'Cuna histórica del surf en el norte'),
('El Palmar',        'España',             'Andalucía',              36.2366,    -6.0682, 'Spot principal de la costa sur española'),
('Famara',           'España',             'Islas Canarias',         29.1172,   -13.5599, 'Extenso beach break en Lanzarote'),
('Razo',             'España',             'Galicia',                43.2882,    -8.6946, 'Spot gallego de gran consistencia'),
('G-Land',           'Indonesia',          'Java Oriental',          -8.7243,   114.3617, 'Mítica izquierda perfecta en la selva'),
('Biarritz',         'Francia',            'Nouvelle-Aquitaine',     43.4832,    -1.5586, 'Lugar de nacimiento del surf en Europa'),
('Waikiki',          'EEUU',               'Hawaii',                 21.2769,  -157.8271, 'Cuna del surf moderno y longboard'),
('Río de Janeiro',   'Brasil',             'Estado de Río',         -22.9836,   -43.2045, 'Beach breaks urbanos icónicos'),
('Pichilemu',        'Chile',              'Región de O''Higgins',  -34.3857,   -72.0048, 'Punta de Lobos, capital chilena del surf'),
('Arugam Bay',       'Sri Lanka',          'Provincia Oriental',      6.8407,    81.8267, 'Derecha muy larga y amigable');
-- =============================================================
--  PERMISOS Y USUARIOS
-- =============================================================

-- crea usuario nuevo con contraseña (añadido IF NOT EXISTS para mayor seguridad)
CREATE USER IF NOT EXISTS 'swelltracker'@'%' IDENTIFIED BY 'SurferAdmin123$';

-- permite acceso a ese usuario
GRANT USAGE ON *.* TO 'swelltracker'@'%';

-- quitale todos los limites que tenga
ALTER USER 'swelltracker'@'%' 
REQUIRE NONE 
WITH MAX_QUERIES_PER_HOUR 0 
MAX_CONNECTIONS_PER_HOUR 0 
MAX_UPDATES_PER_HOUR 0 
MAX_USER_CONNECTIONS 0;

-- dale acceso a la base de datos swelltracker
GRANT ALL PRIVILEGES ON `swelltracker`.* TO 'swelltracker'@'%';

-- recarga la tabla de privilegios
FLUSH PRIVILEGES;