<?php
include_once 'header.php';
include_once 'sidebar.php';
session_start();
?>

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Encaminhamentos &agrave;s OSCs</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/coed/area-restrita">Inicio</a></li>
          <li class="breadcrumb-item active">Encaminhamentos &agrave;s OSCs</li>
        </ol>
      </nav>
    </div>

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pessoas encaminhadas</h5>
              <div id="boxListaEncaminhamentosOscs"></div>
            </div>
          </div>
        </div>
      </div>
    </section>

</main>
