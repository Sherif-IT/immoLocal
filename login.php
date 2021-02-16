
<?php

require 'DBFactory.class.php';
require 'manager/UsersManager.class.php';
require 'model/Users.Class.php';


$db = DBFactory::getMysqlConnexionWithPDO();
$manager = new UsersManager($db)
;
if (isset ($_POST['login']) && isset($_POST['password'])){
      $login =$_POST['login'];
      $password=$_POST['password'];

      $userLog = $manager->getUniqueCon($login,$password); 

      if($userLog==""){
         $erreur ="Nom d'utiliser ou mot de passe incorrect";
      } //LA SESSION COMMENCE QUE APRES IDENTIFICATION
      if( $userLog !="" && $userLog->getLogin() == $login && $userLog->getPassword() == $password ) {
         session_start() ; 
         $_SESSION['login'] = $userLog->getLogin();
         $_SESSION['type'] = $userLog->getType();
         header('location: appartement.php');
      }
}

?>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GesApp</title>
<link href="setting/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="setting/bootstrap.min.js"></script>
<script src="setting/jquery.min.js"></script>
<link href="inc/custom2.css" rel="stylesheet">
<script src="setting/jquery.validate.min.js"></script>
<link href="loginfont-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>
<style>
#img{
    border-radius:10px;
}
#titrecon{
    text-align:center;
}
.erreur {
    color: red;
}
</style>
<body>
<div class="container">
   <div class="row">
      <div class="col-md-5 mx-auto">
         <div id="first">
               <div class="myform form ">
                     <div id="titrecon" class="logo mb-3">
                     AUTHENTIFICATION
                     </div>
                     <form action="login.php" method="post" name="login">
                        <div class="form-group">
                           <label for="nameuser">Login</label>
                           <input type="text" name="login"  class="form-control" id="login" placeholder="Entrez votre login" required >
                        </div>
                        <div class="form-group">
                           <label for="password">Mot de passe</label>
                           <input type="password" name="password" id="password"  class="form-control" placeholder="Entrez votre password" required>
                        </div>
                        <div class="col-md-12 text-center ">
                           <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Connexion</button>
                        </div>
                        <?php
                        if (isset($erreur)){
                              echo $erreur;
                        }
                        ?>
                     </form>

               </div>
         </div>
   </div>
</div>    
</body>