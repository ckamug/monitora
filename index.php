<?php
	//header('location:https://portal.seds.sp.gov.br/coed/login');
	include_once 'configuracoes.php';
	include_once 'classes/requereViews.php';
?>
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Bootstrap CSS -->
		<title>Política Sobre Drogas</title>
		<!-- Favicons -->
		<link href="<?php echo URL ?>assets/img/favicon.png" rel="icon">
  		<link href="<?php echo URL ?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

		<!-- Google Fonts -->
		<link href="https://fonts.gstatic.com" rel="preconnect">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

		<!-- Vendor CSS Files -->
		<link href="<?php echo URL ?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
		<link href="<?php echo URL ?>assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/vendor/quill/quill.snow.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/vendor/quill/quill.bubble.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/vendor/remixicon/remixicon.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/vendor/simple-datatables/style.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/icon-fa/css/all.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css">

		<!-- Template Main CSS File -->
		<link href="<?php echo URL ?>assets/css/style.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/css/fileinput.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo URL ?>assets/css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">


		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js" integrity="sha384-qlmct0AOBiA2VPZkMY3+2WqkHtIQ9lSdAsAn5RUJD/3vA5MKDgSGcdmIv4ycVxyn" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<!-- <script src="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/Gruntfile.min.js"></script> -->
				
		
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
		<script src="<?php echo URL ?>assets/js/fileinput.min.js"></script>
		<script src="<?php echo URL ?>assets/js/jquery.validate.js"></script>
		<script src="<?php echo URL ?>assets/js/jquery.mask.min.js"></script>
		<script src="<?php echo URL ?>assets/js/jquery.maskMoney.min.js"></script>
		<script src="<?php echo URL ?>assets/js/bootstrap-datepicker.js"></script>
		<script src="<?php echo URL ?>assets/locales/bootstrap-datepicker.pt-BR.min.js"></script>
		<script src="<?php echo URL ?>assets/icon-fa/js/all.js"></script>

	</head>
	<body>

		<!-- Carregamento da VIEW -->
		<?php include_once 'public/index.php'?>

	</body>
</html>