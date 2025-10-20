<?php
require_once 'db.php';
require_once 'header.php';
require_once 'functions.php';
session_start();
// Check if user is logged in 
    if(!isset($_SESSION['id'])) {

        $_SESSION['error'] = "Connectez-vous pour accéder à cette page. ";
        header("Location: login.php");
        exit();
    }

?>  

<br>
    <div id="resultats">
<span>
   <h2>Résultats des examens 2024-2025</h2>
    <p>Consultez les résultats des examens pour l'année académique 2024-2025.</p>
</span>
<hr>
</br>
        <div class="resultat-item">
            
            <h3> <li>IAN IC1</li></h3>
            <a href="results/SN IAN1.pdf" class="btn btn-primary" target="_blank">Session normale</a>
            <a href="results/SR IAN1.pdf" class="btn btn-primary" target="_blank">Session de rattrapage</a>
            <h3> <li>IAN IC2</li></h3>
            <a href="results/SN IAN2.pdf" class="btn btn-primary" target="_blank">Session normale</a>
            <a href="results/SR IAN2.pdf" class="btn btn-primary" target="_blank">Session de rattrapage</a>
            <h3> <li>IAN IC3</li></h3>
            <a href="results/SN IAN3.pdf" class="btn btn-primary" target="_blank">Session normale</a>
            <a href="results/SR IAN3.pdf" class="btn btn-primary" target="_blank">Session de rattrapage</a>
</br></br>
            <hr></br>

            <h3> <li>IHN IC1</li></h3
            <a></a>
            <a href="results/SN IHN1.pdf" class="btn btn-primary" target="_blank">Session normale</a>
            <a href="results/SR IHN1.pdf" class="btn btn-primary" target="_blank">Session de rattrapage</a>
            <h3> <li>IHN IC2</li></h3>
             <a href="results/SN IHN2.pdf" class="btn btn-primary" target="_blank">Session normale</a>
            <a href="results/SR IHN2.pdf" class="btn btn-primary" target="_blank">Session de rattrapage</a>
            <h3> <li>IHN IC3</li></h3>
             <a href="results/SN IHN3.pdf" class="btn btn-primary" target="_blank">Session normale</a>
            <a href="results/SR IHN3.pdf" class="btn btn-primary" target="_blank">Session de rattrapage</a>
</br></br>           
            <hr></br>
            <h3>SYNTHESES ANNUELLES</h3>
            <hr></br>
            <h3> <li>IC1</li></h3>
            <a href="results/synthese_ian1.pdf" class="btn btn-primary" target="_blank">Synthese IAN 1</a>
            <a href="results/synthese_ihn1.pdf" class="btn btn-primary" target="_blank">Synthese IHN 1</a>
            <h3> <li>IC2</li></h3>
            <a href="results/synthese_ian2.pdf" class="btn btn-primary" target="_blank">Synthese IAN 2</a>
            <a href="results/synthese_ihn2.pdf" class="btn btn-primary" target="_blank">Synthese IHN 2</a>
            <h3> <li>IC3</li></h3>
            <a href="results/synthese_ian3.pdf" class="btn btn-primary" target="_blank">Synthese IAN 3</a>
            <a href="results/synthese_ihn3.pdf" class="btn btn-primary" target="_blank">Synthese IHN 3</a>
  </br></br>           
<hr></br>
            <h3>PV D'ADMISSION AU NIVEAU 2</h3>
            <hr></br>
              <a href="results/admission_ian1.pdf" class="btn btn-primary" target="_blank">Admission IAN 1</a>
            <a href="results/admission_ihn1.pdf" class="btn btn-primary" target="_blank">Admission IHN 2</a>
            </br></br> 
           <h3>PV D'ADMISSION AU NIVEAU 3</h3>
    
            <hr></br>
              <a href="results/admission_ian2.pdf" class="btn btn-primary" target="_blank">Admission IAN 2</a>
            <a href="results/admission_ihn2.pdf" class="btn btn-primary" target="_blank">Admission IHN 2</a>
            
        </div>
</div> 





    
<?php require_once 'footer.php'; ?>

