<?php
class Users{
 //une classe a attributs ,constructeurs, Getteurs,Setteurs
    protected $erreurs = array(),
    $id,
    $login,
    $password,
    $type,
    $auteur;
    

    public function hydrate($donnees){
        foreach ($donnees as $clé => $valeur){
            $methode = 'set'.ucfirst($clé);
            if (is_callable(array($this, $methode))){
                $this->$methode($valeur);
            }
        }
    }

//Initialisation des attributs avec le constructeur

    public function __construct($valeurs = array()){
        // Si on a spécifié des valeurs, alors on hydrate l'objet.
        if (!empty($valeurs)) {
            $this->hydrate($valeurs);
        }
    }

    public function isValid(){
        return !(empty($this->login));
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

    public function getLogin(){
        return $this->login;
    }
    public function getPassword(){
        return $this->password;
    }
    public function getType(){
        return $this->type;
    }
    public function getAuteur(){
        return $this->auteur;
    }


//Setteurs ou mutateurs pour modifier valeure attribut
   
    public function setId($id){
        $this->id = $id;
    }

    public function setLogin($login){
        $this->login = $login;
    }

    public function setPassword($password){
        $this->password = $password;
    }
    public function setType($type){
        $this->type = $type;
    }

    public function setAuteur($auteur){
        $this->auteur = $auteur;
    }
}

?>