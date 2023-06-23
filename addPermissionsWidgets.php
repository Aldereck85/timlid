<?php

/* include "include/db-conn.php";

$stmt = $conn->prepare('SELECT u.id, u.role_id
    FROM usuarios AS u
    INNER JOIN empleados AS e ON u.id = e.PKEmpleado
    ORDER BY u.id DESC');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT wg.id, wg.widget FROM widgets AS wg');
$stmt->execute();
$widgets = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    foreach ($widgets as $widget) {
        $stmt = $conn->prepare('INSERT INTO permisos_widgets (FKUsuario, FKWidget, Permiso) VALUES (:usuario, :widget, :permiso)');
        $stmt->execute([':usuario' => $user['id'], ':widget' => $widget['id'], ':permiso' => 1]);
    }
}
 */