<?php session_start() ; ?>
<?php

if (!isset($_SESSION['login'])){
    header ( 'location: login.php');
}

require 'DBFactory.class.php';
require 'manager/AppartementManager.class.php';
require 'model/Appartement.class.php';


$db = DBFactory::getMysqlConnexionWithPDO();


//Instanciation manager
$manager = new AppartementManager($db);

//ajout photo
if (isset($_FILES['photo'])){
    $photo=$_FILES['photo']['name'];
    $id=$_POST['id2'];
    $manager->updatePhoto($photo,$id);
//deplacer photo dans serveur
    $dossier='images/';
    $nom_fichier=basename($_FILES['photo']['name']);
    move_uploaded_file($_FILES['photo']['tmp_name'],$dossier.$nom_fichier);

    $message='photo modifiée avec succés';

}

//Instanciation objet appartement sur soumission formulaire ,creation d'une entree en bdd
if  ( isset($_POST['types']) ){
    $appartement = new Appartement(array(
    'types'=> $_POST['types'],
    'adresse'=> $_POST['adresse'],
    'paiement'=>$_POST['paiement'],
    'prix'=>$_POST['prix'],
    'detail'=>$_POST['detail'],
    'auteur'=>$_SESSION['login']
    ));

//capter id lorsqu'on modifie ,(l'id n'est pas captable si on ajoute) voir js e t ajax
    if(isset($_POST['id'])){
        $appartement->setId($_POST['id']);

    }


if ($appartement->isValid()){
    $manager->save($appartement);
    $message= $appartement->isNew()  ? 'appartement enregistré !' : 'Appartement modifié !';
}
else{
    $erreurs = $appartement->erreurs();
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
<?php include ('inc/nav.php'); ?>
<div class="container" >
    <br><br><br><br>
    
    <div id="titreVue"><h1>LISTE DES APPARTEMENTS</h1></div><br>
    <?php

        if (isset($message) && $message != ""){
            echo '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            '.$message.'
             </div>';
        }
        ?>

        <a data-role="add" class="btn btn-success btn-sm pull-left">Ajouter Appartement </a><br><br>

        <table id="example1" class="table table-bordered table-striped">
            <thead>
             <tr>
                <th>TYPE</th>
                <th>ADRESSE</th>
                <th>PAIEMENT</th>
                <th>PRIX</th>
                <th>DETAIL</th>
                <th>PHOTO</th>
                <th>AUTEUR </th>
                <th>ACTION</th>

        </tr>
            </thead>
        <tbody>
            <?php
                foreach($manager->getList() as $appartement){
                    $action1='';
                    $action2='';
                    $action3='';
                    //boutons visibles aux admin.( utilisateurs simples se limitent à modifier que ce qqu'ils ont ajouté )
                    if( $appartement->getAuteur() == $_SESSION['login'] && $_SESSION['type'] == 'Utilisateur'){
                        $action1='<a class="btn btn-primary btn-xs" data-role="update" data-comment_id='.$appartement->getId().'>Modifier</a>';
                        $action2='<a id="supprimer" href="?supprimer='.$appartement->getId().'" class="btn btn-danger btn-xs">Supprimer</a>';
                        $action3='<a class="btn btn-warning btn-xs" data-role="photo" data-comment_id='.$appartement->getId().'>Editer photo</a>';
                    } if($_SESSION['type'] == 'Admin'){
                        $action1='<a class="btn btn-primary btn-xs" data-role="update" data-comment_id='.$appartement->getId().'>Modifier</a>';
                        $action2='<a id="supprimer" href="?supprimer='.$appartement->getId().'" class="btn btn-danger btn-xs">Supprimer</a>';
                        $action3='<a class="btn btn-warning btn-xs" data-role="photo" data-comment_id='.$appartement->getId().'>Editer photo</a>';
                    }
                    echo '<tr>
                    <td>',$appartement->getTypes(),'</td>
                    <td>',$appartement->getAdresse(),'</td>
                    <td>',$appartement->getPaiement(),'</td>
                    <td>',$appartement->getPrix(),'</td>
                    <td>',$appartement->getDetail(),'</td>
                    <td>',$appartement->getPhoto(),'</td>
                    <td>',$appartement->getAuteur(),'</td>
                    
                    <td class="col-xs-3">
                     
                    '.$action1.'&nbsp '.$action2.'&nbsp '.$action3.'
                    </td>
                        </tr>'."\n";

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
                <form action="appartement.php" method="post" role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <select name="types" id="types" class="form-control" required>
                            <option value="">Choisir le type d'appartement</option>
                            <option value="Appartement à louer">Appartement à louer</option>
                            <option value="Appartement à vendre">Appartement à vendre</option>
                        </select>
                        <input type="hidden" id="id" name="id" placeholder="" value="" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <input type="hidden" id="adresse" name="adresse" placeholder="Entrez l'adresse" value="" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <select name="paiement" id="paiement" class="form-control">
                            <option value="">Choisir le type de paiement</option>
                            <option value="Par nuité">Nuité</option>
                            <option value="Par mois">Mois</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="number" id="prix" name="prix" placeholder="Entrez le prix" value="" class="form-control" required/>
                    </div>
                    <div class="form-group">
                        <textarea id="detail" name="detail" placeholder="Informations supplémentaires (nombre de chambre, de salon...)" value="" class="form-control" ></textarea>
                    </div>
                    <div class="modal-footer">
                        <button id="enreg" type="submit" class="btn btn-primary btn-xs">Enregistrer</button>
                        <button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editPhoto" data-keyboard="false" data-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="titreForm" id="titreFormEnreg">ENREGISTREMENT DE CLASSE</h4>
                </div>
                <div class="modal-body">
                        <form action="appartement.php" method="post" role="form" enctype="multipart/form-data">
                        <label>Telecharger une photo</label>
                        <div class="form-group">
                            <input type="file" id="adresse" name="photo" placeholder="uploadez votre photo" value="" class="form-control" required/>
                        </div>
                        <input type="hidden" id="id2" name="id2" placeholder="" value="" class="form-control" required/>
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
        //ajouter enregistrement apres click
          $(document).on('click','a[data-role=add]',function(){
              $('#types').val('');
              $('#adresse').val('');
              $('#prix').val('');
              $('#paiement').val('');
              $('#detail').val('');
              $('#id').val('');

              $('#ajoutApp').modal('toggle');
          });

          $(document).on('click','a[data-role=photo]',function(){
            var comment_id=  $(this).data('comment_id') ;
            $('#id2').val(comment_id);
            $('#editPhoto').modal('toggle');
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
          //modification appartement
          $(document).on('click','a[data-role=update]',function(){
              //recuperons d'abord l'id de l'ap selectionné
            var comment_id=$(this).data('comment_id') ;
            $('#id').val(comment_id);

            $.ajax({
                url :'fonctions/getAppartement.php',
                type:'POST',
                dataType:'JSON',
                data:{ id: comment_id},
                success: function(data){
                    $('#types').val(data.types);
                    $('#prix').val(data.prix);
                    $('#adresse').val(data.adresse);
                    $('#paiement').val(data.paiement);
                    $('#detail').val(data.detail);
                 }
            })
            $('#ajoutApp').modal('toggle');
          });

      });

</script>
</body>
</html>