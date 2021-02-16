<?php
class Clients{
 //une classe a attributs ,constructeurs, Getteurs,Setteurs
    protected $erreurs = array(),
    $id,
    $appartement,
    $nom,
    $prenom,
    $email,
    $teleph
    ;
    

    public function hydrate($donnees){
        foreach ($donnees as $cle => $valeur){
            $methode = 'set'.ucfirst($cle);
            if (is_callable(array($this, $methode))){
                $this->$methode($valeur);
            }
        }
    }

//Initialisation des attributs avec le constructeur

    public function __construct($valeurs = array()){
        // Si on a specifie des valeurs, alors on hydrate l'objet.
        if (!empty($valeurs)) {
            $this->hydrate($valeurs);
        }
    }

    public function isValid(){
        return !(empty($this->nom));
    }

    public function erreurs(){
        return $this->erreurs;
    }

    public function isNew(){
        return empty($this->id);
    }

//getteurs ou accesseurs pour obtenir valeure attributs depuis exterieur de la classe

    public function getId(){
        return $this->id;
    }
    public function getAppartement(){
        return $this->appartement;
    }

    public function getNom(){
        return $this->nom;
    }
    public function getPrenom(){
        return $this->prenom;
    }
    
    public function getEmail(){
        return $this->email;
    }
    public function getTelephone(){
        return $this->teleph;
    }

//Setteurs ou mutateurs pour modifier valeure attribut
   
    public function setId($id){
        $this->id = $id;
    }
    public function setAppartement($appartement){
        $this->appartement = $appartement;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function setPrenom($prenom){
        $this->prenom = $prenom;
    }
  

    public function setEmail($email){
        $this->email = $email;
    }
    public function setTelephone($telephone){
        $this->teleph = $telephone;
    }
}

?>