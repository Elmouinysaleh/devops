<?php 
require_once('classes/actions.class.php');
$actionClass = new Actions();

$num_classes = $actionClass->getNumberOfClasses();
$num_students = $actionClass->getNumberOfStudents();
?>

<div class="container-md py-3">
    <?php if(isset($_SESSION['flashdata']) && !empty($_SESSION['flashdata'])): ?>
        <div class="flashdata flashdata-<?= $_SESSION['flashdata']['type'] ?>">
            <?= $_SESSION['flashdata']['message'] ?>
        </div>
    <?php unset($_SESSION['flashdata']); endif; ?>

    <!-- Section du Tableau de Bord -->
<div class="page-title">Tableau de Bord</div>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="dashboard-item-icon">
                    <i class="fas fa-users fa-3x"></i>
                </div>
                <h5 class="card-title">Nombre d'Étudiants</h5>
                <p class="card-text"><?= $num_students ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="dashboard-item-icon">
                    <i class="fas fa-chalkboard-teacher fa-3x"></i>
                </div>
                <h5 class="card-title">Nombre de Classes</h5>
                <p class="card-text"><?= $num_classes ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <div class="dashboard-item-icon">
                    <i class="fas fa-calendar-day fa-3x"></i>
                </div>
                <h5 class="card-title">Date Actuelle</h5>
                <p class="card-text"><?= date('Y-m-d') ?></p>
            </div>
        </div>
    </div>
</div>
<!-- Fin de la Section du Tableau de Bord -->

    
</div>

