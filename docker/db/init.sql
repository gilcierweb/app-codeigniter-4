CREATE DATABASE IF NOT EXISTS app_codeigniter_ci4;
GRANT ALL PRIVILEGES ON app_codeigniter_ci4.* TO 'root'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL PRIVILEGES ON app_codeigniter_ci4.* TO 'root'@'%' IDENTIFIED BY 'secret';
FLUSH PRIVILEGES;