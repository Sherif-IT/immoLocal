<?php session_start() ; ?>
<?php
if (!isset($_SESSION['login'])){
    header ( 'location: index.php');
}
require 'DBFactory.class.php';
require 'manager/UsersManager.class.php';
require 'model/Users.Class.php';

$db = DBFactory::getMysqlConnexionWithPDO();

//Instanciation manager
$manager = new UsersManager($db);

//Instanciation objet users sur soumission formulaire
if  ( isset($_POST['login']) ) {
   
    $user = new Users(array(
    'login'=> $_POST['login'],
    'password'=> $_POST['password'],
    'type'=> $_POST['type'],
    'auteur'=> $_SESSION['login']
   
    ));

//capter id lorsqu'on modifie ,(l'id n'est pas captable si on ajoute) voir js e t ajax
    if(isset($_POST['id'])){
        $user->setId($_POST['id']);

    }


if ($user->isValid()){
    $manager->save($user);
    $message= $user->isNew()  ? 'users enregistré !' : 'users modifié !';
}
else{
    $erreurs = $user->erreurs();
}

}

if (isset($_GET['supprimer'])){
    $manager->delete((int) $_GET['supprimer']);
    $message='suppression reussie';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GesApp</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="inc/custom.css" rel="stylesheet">
</head>
<style>
    #titreVue{
        text-align:center;
    }
</style>
<?php include ('inc/style.php'); ?>
<body>
<?php include ('inc/nav.php');  ;?>
<div class="container" >
    <br><br><br><br>
    
    <div id="titreVue"><h1>LISTE DES USERS</h1></div><br>
    <?php

        if (isset($message) && $message != ""){
            echo '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            '.$message.'
             </div>';
        }
        ?>

        <a data-role="add" class="btn btn-success btn-sm pull-left">Ajouter users </a><br><br>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
             <tr>
                <th>Login</th>
                <th>Password</th>
                <th>Type</th>
                <th>Auteur</th>
                <th>Action</th>
               
        </tr>
            </thead>
        <tbody>
            <?php   
    echo $_SESSION['login'] ; 
                foreach( $manager->getList() as $user){
                    echo '<tr>
                    <td>',$user->getLogin(),' </td>
                    <td>',$user->getPassword(),' </td>
                    <td>',$user->getType(),' </td>
                    <td>',$user->getAuteur(),' </td>
                   
                    <td class="col-xs-2">
                    <a class="btn btn-primary btn-xs" data-role="update" data-comment_id=', $user->getId().' >Modifier </a> |
                    <a id="supprimer" href=" ?supprimer=',$user->getId(), ' " class="btn btn-danger btn-xs">Supprimer</a>
                    </td>
                        </tr>',"\n";

                    }
                   

            ?>
        </tbody>
    </table>
</div>
  <!-- Modal -->
<div class="modal fade" id="ajoutApp" data-keyboard="false" data-backdrop="static" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="titreForm" id="titreFormEnreg">ENREGISTREMENT DE CLASSE</h4>
            </div>
            <div class="modal-body">
                <form action="users.php" method="post" role="form" enctype="multipart/form-data">

                    <div class="form-group">
                        <input type="text" id="login" name="login" placeholder="Entrez le login" value="" class="form-control" required/>
                    </div>

                    <div class="form-group">
                        <input type="hidden" id="id" name="id" placeholder="" value="" class="form-control" required/>
                    </div>

                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Entrez le password" value="" class="form-control" required/>
                    </div>

                    <select name="type" id="type" class="form-control" required>
                        <option value="">Choisir le type </option>
                        <option value="Utilisateur">Utilisateur</option>
                        <option value="Admin">Admin</option>
                    </select>

                    <div class="modal-footer">
                        <button id="enreg" type="submit" class="btn btn-primary btn-xs">Enregistrer</button>
                        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--fin modal-->     
<script src="bootstrap/jquery.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>

<script>
      $(document).ready(function(){

$(document).on('click','a[data-role=add]',function(){
    $('#login').val('');
    $('#type').val('');
    $('#password').val('');
   
    $('#id').val('');

    $('#ajoutApp').modal('toggle');
});
 //supprimer enregistrement apres click
var supprimerLinks=document.querySelectorAll('#supprimer');
$(supprimerLinks).click(function(){
    var txt ;
    var r = confirm("l'enregistrement sera supprimé,vous confirmez?");
    if (r==true){
        return true;
    } else{
    return false;
  }
    
});
//.......
$(document).on('click','a[data-role=update]',function(){
  var comment_id=$(this).data('comment_id') ;
  $('#id').val(comment_id);
  $.ajax({
      url :'fonctions/getUser.php',
      type:'POST',
      dataType:'JSON',
      data:{ id: comment_id},
      success: function(data){
          $('#login').val(data.login);
          $('#password').val(data.password);
          $('#type').val(data.type);
         
       }
  });
  $('#ajoutApp').modal('toggle');
});

});

</script>
</body>
</html>