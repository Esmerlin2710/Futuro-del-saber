
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$campos = [
    "matricula",
    "nombres",
    "apellidos",
    "grado",
    "fecha",
    "seccion"
];

$datos = [];

foreach ($campos as $campo) {
    if (
        !isset($_POST[$campo]) ||
        !is_string($_POST[$campo]) ||
        trim($_POST[$campo]) === ""
    ) {
        exit("Falta completar el campo: " . $campo);
    }

    $datos[$campo] = trim($_POST[$campo]);
}

$asignaturas = [
    "espanol" => "Lengua Española",
    "matematicas" => "Matemáticas",
    "sociales" => "Ciencias Sociales",
    "naturales" => "Ciencias Naturales",
    "ingles" => "Inglés",
    "informatica" => "Informática"
];

$evaluaciones = [
    "practicas",
    "tareas",
    "parcial",
    "final"
];

if (
    !isset($_POST["notas"]) ||
    !is_array($_POST["notas"])
) {
    exit("No se recibieron las calificaciones.");
}

$materias = [];

foreach ($asignaturas as $codigo => $nombre) {

    if (
        !isset($_POST["notas"][$codigo]) ||
        !is_array($_POST["notas"][$codigo])
    ) {
        exit("Faltan las notas de: " . $nombre);
    }

    $notas = [];

    foreach ($evaluaciones as $evaluacion) {

        $valor = $_POST["notas"][$codigo][$evaluacion] ?? null;

        if (
            !is_string($valor) ||
            trim($valor) === "" ||
            !is_numeric($valor)
        ) {
            exit("Calificación inválida en: " . $nombre);
        }

        $nota = (float) $valor;

        if (
            !is_finite($nota) ||
            $nota < 0 ||
            $nota > 100
        ) {
            exit("Las notas deben estar entre 0 y 100.");
        }

        $notas[] = $nota;
    }

    $promedioMateria = round(
        array_sum($notas) / count($notas),
        2
    );

    $materias[$nombre] = $promedioMateria;
}

$promedioGeneral = round(
    array_sum($materias) / count($materias),
    2
);

// CONTAR ASIGNATURAS APROBADAS Y REPROBADAS
$aprobadas = 0;
$reprobadas = 0;

foreach ($materias as $promedio) {
    if ($promedio >= 70) {
        $aprobadas++;
    } else {
        $reprobadas++;
    }
}

// GUARDAR INFORMACIÓN EN LA SESIÓN
$_SESSION["reporte"] = [
    "estudiante" => $datos["nombres"] . " " . $datos["apellidos"],
    "matricula" => $datos["matricula"],
    "grado" => $datos["grado"] . " " . $datos["seccion"],
    "fecha" => $datos["fecha"],
    "materias" => $materias,
    "promedioGeneral" => $promedioGeneral,
    "aprobadas" => $aprobadas,
    "reprobadas" => $reprobadas
];

// REDIRIGIR AL REPORTE
header("Location: reportes.php");
exit;
?>
