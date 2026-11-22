
<?php
session_start();

// Comprobar que exista un reporte
if (!isset($_SESSION["reporte"])) {
    header("Location: index.html");
    exit;
}

// Recuperar los datos
$reporte = $_SESSION["reporte"];

// Proteger los textos que se muestran en HTML
function mostrar($texto) {
    return htmlspecialchars(
        (string) $texto,
        ENT_QUOTES,
        "UTF-8"
    );
}
?>

<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Reporte académico</title>
    <link rel="stylesheet" href="css/estilos.css" />
  </head>

  <body class="reporte">
    <header>
      <h1>Boletín de calificaciones</h1>

      <p id="estudiante">
        <?= mostrar($reporte["estudiante"]) ?>
        ·
        <?= mostrar($reporte["grado"]) ?>
      </p>
    </header>

    <!-- RESUMEN DE RESULTADOS -->
    <section class="resumen">
      <div>
        <p id="promedioGen">
          <?= number_format($reporte["promedioGeneral"], 2) ?>
        </p>

        <small>Promedio general</small>
      </div>

      <div>
        <p id="aprobadas">
          <?= $reporte["aprobadas"] ?>
        </p>

        <small>Aprobadas</small>
      </div>

      <div>
        <p id="reprobadas">
          <?= $reporte["reprobadas"] ?>
        </p>

        <small>Reprobadas</small>
      </div>
    </section>

    <!-- TABLA DE CALIFICACIONES -->
    <section>
      <div class="tabla-contenedor">
        <table>
          <thead>
            <tr>
              <th>Asignatura</th>
              <th>Promedio</th>
              <th>Estado</th>
            </tr>
          </thead>

          <tbody id="tablaCalificaciones">
            <?php foreach ($reporte["materias"] as $nombre => $nota): ?>
              <tr>
                <td>
                  <?= mostrar($nombre) ?>
                </td>

                <td>
                  <?= number_format($nota, 2) ?>
                </td>

                <td class="<?= $nota >= 70 ? 'aprobada' : 'reprobada' ?>">
                  <?= $nota >= 70 ? 'Aprobada' : 'Reprobada' ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <footer>
      <p>Reporte académico del estudiante.</p>
      <a href="index.html">Registrar otro estudiante</a>
    </footer>
  </body>
</html>
