
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> AHN CONNECT</title>
        <style>
#formulaire input{
    width: 50%;
    padding: 10px;  
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}         
    </style>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
            <header>
                <div class="conatainer header-container">
                    <div class="logo">
                        <i class="fas fa-graduation-cap"></i>
                        <h1>AHN CONNECT</h1>
                    </div>
                 <?php include 'navigation1.php'; ?>  
                </div> 
           </header>

           <div class="container">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['error'] ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION['successs'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?= $_SESSION['seccess'] ?>
                    </div>
                    
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

