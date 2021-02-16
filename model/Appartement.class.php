<?php
class Appartement{
 //une classe a attributs ,constructeurs, Getteurs,Setteurs
    protected $erreurs = array(),
    $id,
    $types,
    $paiement,
    $prix,
    $detail,
    $auteur,
    $photo;

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
        return !(empty($this->types));
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

    public function getTypes(){
        return $this->types;
    }
    public function getAdresse(){
        return $this->adresse;
    }
    public function getPaiement(){
        return $this->paiement;
    }
    public function getPrix(){
        return $this->prix;
    }
    public function getDetail(){
        return $this->detail;
    }
    public function getAuteur(){
        return $this->auteur;
    }
    public function getPhoto(){
        return $this->photo;
    }

//Setteurs ou mutateurs pour modifier valeure attribut
   
    public function setId($id){
        $this->id = $id;
    }

    public function setTypes($types){
        $this->types = $types;
    }
    public function setAdresse($adresse){
        $this->adresse = $adresse;
    }
    public function setPaiement($paiement){
        $this->paiement = $paiement;
    }
    public function setPrix($prix){
        $this->prix = $prix;
    }
    public function setDetail($detail){
        $this->detail = $detail;
    }
    public function setAuteur($auteur){
        $this->auteur = $auteur;
    }
    public function setPhoto($photo){
        $this->auteur = $photo;
    }
}

?>