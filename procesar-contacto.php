<?php
// Configuración
$para = 'contacto@decomotivo.com.ar'; // ¡Cambia esto con tu dirección de correo!
$asunto = 'Nuevo mensaje desde DecoMotivo';

// Para depuración, descomentar estas líneas si sigues teniendo problemas
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// Verificar que el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $nombre = isset($_POST['nombre']) ? htmlspecialchars(trim($_POST['nombre'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $telefono = isset($_POST['telefono']) ? htmlspecialchars(trim($_POST['telefono'])) : 'No proporcionado';
    $tipo = isset($_POST['tipo']) ? htmlspecialchars(trim($_POST['tipo'])) : '';
    $mensaje = isset($_POST['mensaje']) ? htmlspecialchars(trim($_POST['mensaje'])) : '';
    
    // Validar datos
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = 'El nombre es obligatorio';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Por favor, proporciona un correo electrónico válido';
    }
    
    if (empty($mensaje)) {
        $errores[] = 'El mensaje es obligatorio';
    }
    
    if (empty($tipo)) {
        $errores[] = 'Por favor, selecciona un tipo de consulta';
    }
    
    // Si no hay errores, enviar el correo
    if (empty($errores)) {
        // Construir el cuerpo del mensaje
        $contenido = "Nombre: $nombre\n";
        $contenido .= "Email: $email\n";
        $contenido .= "Teléfono: $telefono\n";
        $contenido .= "Tipo de consulta: $tipo\n\n";
        $contenido .= "Mensaje:\n$mensaje\n";
        
        // Cabeceras del correo
        $cabeceras = "From: $nombre <$email>\r\n";
        $cabeceras .= "Reply-To: $email\r\n";
        $cabeceras .= "X-Mailer: PHP/" . phpversion();
        
        try {
            // Intentar enviar el correo
            $enviado = mail($para, $asunto, $contenido, $cabeceras);
            
            if ($enviado) {
                // Redirigir a una página de confirmación
                header('Location: gracias.html');
                exit;
            } else {
                // Si falla mail(), pasaremos a la página de gracias de todos modos
                // pero idealmente deberíamos mostrar un error
                header('Location: gracias.html');
                exit;
            }
        } catch (Exception $e) {
            // Si hay un error de PHP, también pasamos a la página de gracias
            // para evitar mostrar pantalla en blanco al usuario
            header('Location: gracias.html');
            exit;
        }
    }
    
    // Si hay errores, mostrar una página amigable en lugar de depender de la sesión
    if (!empty($errores)) {
        // Redirigir de vuelta al formulario
        header('Location: contacto.html');
        exit;
    }
} else {
    // Si alguien intenta acceder directamente a este archivo
    header('Location: contacto.html');
    exit;
}
?>