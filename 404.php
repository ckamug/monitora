<?php
	include_once 'configuracoes.php';
	include_once 'classes/requereViews.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Página Não Encontrada</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?php echo URL ?>assets/img/favicon.png" rel="icon">
  <link href="<?php echo URL ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo URL ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo URL ?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo URL ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo URL ?>assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?php echo URL ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?php echo URL ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo URL ?>assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo URL ?>assets/css/style.css" rel="stylesheet">


  <!-- Vendor JS Files -->
  <script src="<?php echo URL ?>assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?php echo URL ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo URL ?>assets/vendor/chart.js/chart.min.js"></script>
  <script src="<?php echo URL ?>assets/vendor/echarts/echarts.min.js"></script>
  <script src="<?php echo URL ?>assets/vendor/quill/quill.min.js"></script>
  <script src="<?php echo URL ?>assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?php echo URL ?>assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="<?php echo URL ?>assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="<?php echo URL ?>assets/js/main.js"></script>
    
</head>

<body>

  <main>
    <div class="container">

      <section class="section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
        <h1>404</h1>
        <h2>A página que você tentou acessar não existe.</h2>
        <a class="btn" href="/home">Voltar para o inicio</a>
        <img src="<?php echo URL ?>assets/img/not-found.svg" class="img-fluid py-5" alt="Page Not Found">

      </section>

    </div>
  </main>

</body>

</html>