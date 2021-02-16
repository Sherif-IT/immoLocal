<?php




require 'DBFactory.class.php';
require 'manager/AppartementManager.class.php';
require 'manager/ClientsManager.class.php';

require 'model/Appartement.class.php';
require 'model/Clients.class.php';


$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new AppartementManager($db);
$managerClient = new ClientsManager($db);

if( isset($_POST['nom']) ){

    $client = new Clients(array(
    'appartement'=>$_POST['appartement'],
    'nom'=> $_POST['nom'],
    'prenom'=> $_POST['prenom'],
    'email'=>$_POST['email']
    ));



if ($client->isValid()){
    $managerClient->save($client);
}
else{
    $erreurs = $client->erreurs();
}
$message='appartement enregistré avec succés';


}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Appartement</title>
    <link rel="icon" href="img/core-img/favicon.ico">
    <link rel="stylesheet" href="style.css">
    
</head>
<style>
    #centrer{
        text-align:center;
    }
        
    </style>
<body>

  <!--
   <div id="preloader">
        <div class="south-load"></div>
    </div>
-->
    <header class="header-area">
        <div class="top-header-area">
            <div class="h-100 d-md-flex justify-content-between align-items-center">
                <div class="email-address">
                    <a href="#">sherif@gmail.com</a>
                </div>
                <div class="phone-number d-flex">
                    <div class="icon">
                        <img src="img/phone-call.png" alt="">
                    </div>
                    <div class="number">
                        <a href="#">+221 77 898 76 54</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-header-area" id="stickyHeader">
            <div class="classy-nav-container breakpoint-off">
                <nav class="classy-navbar justify-content-between" id="southNav">

                    <a class="nav-brand" href="index.html"><img src="img/logo.png" alt=""></a>

                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>

                    <div class="classy-menu">

                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>

                        <div class="classynav">
                            <ul>
                                <li><a href="index.html">Appartements</a></li>
                            </ul>
                            <a href="login.php" class="searchbtn">Connexion</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>

    <section class="hero-area">
        <div class="hero-slides owl-carousel">
            <div class="single-hero-slide bg-img" style="background-image: url(img/hero1.jpg);">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="hero-slides-content">
                                <h2 data-animation="fadeInUp" data-delay="100ms">Trouvez votre appartement</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-hero-slide bg-img" style="background-image: url(img/hero2.jpg);">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="hero-slides-content">
                                <h2 data-animation="fadeInUp" data-delay="100ms">Trouvez votre appartement</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="single-hero-slide bg-img" style="background-image: url(img/hero3.jpg);">
                <div class="container h-100">
                    <div class="row h-100 align-items-center">
                        <div class="col-12">
                            <div class="hero-slides-content">
                                <h2 data-animation="fadeInUp" data-delay="100ms">Trouvez votre appartement</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-properties-area section-padding-100-50">
        
        <?php
        if (isset($message) && $message != ""){
        echo '<div class="alert alert-success alert-dismissible w-50 center">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        '   .$message.'
        </div>';
        }
        ?>
        <div class="container">
            <div class="row">
               <?php
                  foreach($manager->getList() as $appartement){
                     echo'<div class="col-12 col-md-6 col-xl-4">
                    <div class="single-featured-property mb-50 wow fadeInUp" data-wow-delay="100ms">
                        <div class="property-thumb">
                            <img src="images/',$appartement->getPhoto(),'" alt="">

                            <div class="tag">
                                <span>',$appartement->getTypes(),'</span>
                            </div>
                            <div class="list-price">
                                <p>',$appartement->getPrix(),'F</p>
                            </div>
                        </div>
                        <div class="property-content">
                            <h5>',$appartement->getAdresse(),'</h5>
                            <p class="location"><img src="img/location.png" alt="">',$appartement->getPaiement(),'</p>
                            <p>',$appartement->getDetail(),'</p> <div id="centrer">
                            <a data-role="reserver"  class="btn btn-warning btn-sm p-1 " data-comment_id=',$appartement->getId(),'>Reserver </a>
                        </div></div>
                    </div>
                </div>';
                  }
                

                ?>
            </div>
        </div>
         
    </section>
    <!-- Modal -->
    <div class="modal  fade" id="reservation" data-keyboard="false" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="titreForm" id="titreFormEnreg">ENREGISTREMENT DE CLASSE</h4>
            </div>
            <div class="modal-body">
                
                <form action="index.php" method="post" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-4">
                        <div class='photoAp'> <img name="imageView" width="250" height="130" src=""> </div>
                        </div>
                        <div class="col-8">
                            <h6>details :<span id="details"></span>  </h6>  <br>
                            <h6>prix :<span id="prix"></span></h6><br>
                             
                            <h6>type :<span id="type"></span></h6> 
                            
                       </div>

                    </div>
                    </div>
                    <input type="number" id="appartement" name="appartement" placeholder="" value="" class="form-control" required/>
                    <div class="form-group">
                        <input type="text" id="nom" name="nom" placeholder="Entrez votre nom" value="" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <input type="text" id="prenom" name="prenom" placeholder="Entrez votre prenom" value="" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Entrez votre adresse email" value="" class="form-control" required/>
                    </div>
                    <div class="form-group">
                       <!-- <input type="text" id="telephone" name="telephone" placeholder="Entrez votre numero de telephone" value="" class="form-control" required/>-->
                    </div>
                    
                    <div class="modal-footer">
                        <button id="enreg" type="submit" class="btn btn-primary btn-xs" >Reserver</button>
                        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Annuler</button>
                    </div>
                    </form>
                </div>
                </div>
                </div>
            </div>
    <script src="bootstrap/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins.js"></script>
    <script src="js/classy-nav.min.js"></script>
    <script src="js/jquery-ui.min.js"></script>
    <script src="js/active.js"></script>
    <script>
     $(document).ready(function(){
        //ajouter enregistrement apres click
          $(document).on('click','a[data-role=reserver]',function(){
            var comment_id=$(this).data('comment_id') ;
            //on utilise val pour inserer donnee dans un input
            $('#appartement').val(comment_id);
           // console.log('c est fait');
           $.ajax({
                url :'fonctions/getPhotoAppartement.php',
                type:'POST',
                dataType:'JSON',
                data:{ id: comment_id},
                success: function(data){
                   document.images["imageView"].src="images/"+data.photo;
                   document.getElementById("prix").innerHTML = data.prix;
                   document.getElementById("details").innerHTML = data.detail;
                   document.getElementById("type").innerHTML = data.types;

                 }
            })  
            $('#reservation').modal('toggle');   
        });
        //on click 'annuler' photo ap:remove $photo
        
        })
    </script>
</body>

</html>