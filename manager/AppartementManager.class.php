<?php
class AppartementManager{

    protected $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function add(Appartement $appartement){
        $requete = $this->db->prepare('INSERT INTO appartement SET 
        types = :types,
        adresse = :adresse,
        paiement = :paiement,
        prix = :prix,
        detail = :detail,
        auteur = :auteur
        ');
        $requete->bindValue(':types', $appartement->getTypes());
        $requete->bindValue(':adresse', $appartement->getAdresse());
        $requete->bindValue(':paiement', $appartement->getPaiement());
        $requete->bindValue(':prix', $appartement->getPrix());
        $requete->bindValue(':detail', $appartement->getDetail());
        $requete->bindValue(':auteur', $appartement->getAuteur());
        
        $requete->execute();
    }

    public function count(){
        return $this->db->query('SELECT COUNT(*) FROM appartement')->fetchColumn();
    }

    public function delete($id){
        $this->db->exec('DELETE FROM appartement WHERE id = '. $id);
    }


    public function exists($info){
        $q = $this->db->prepare('SELECT COUNT(*) FROM appartement WHERE types = :types');
        $q->execute(array(':types' => $info));
        return (bool) $q->fetchColumn();
    }

    public function getUnique($id){
        $requete = $this->db->prepare('SELECT * FROM appartement WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Appartement');
        return $requete->fetch();
    }
   
    
    public function getList($debut = 0, $limite = 4){
        $sql = 'SELECT * FROM appartement ORDER BY id DESC';
        // On vérifie l'intégrité des paramètres fournis.
        if ($debut != -1 || $limite != -1){
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }
        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Appartement');
        $liste = $requete->fetchAll();
        $requete->closeCursor();
        return $liste;
    }

    public function save(Appartement $appartement){
        if ($appartement->isValid()){
            $appartement->isNew() ? $this->add($appartement) : $this->update($appartement);
        }
        else{
            throw new RuntimeException('');
        }
    }

    public function update(Appartement $Appartement){
        $q = $this->db->prepare('UPDATE appartement SET types = :types,prix = :prix,adresse = :adresse,paiement = :paiement,auteur = :auteur ,detail = :detail  WHERE id = :id');
        $q->bindValue(':types', $Appartement->getTypes());
        $q->bindValue(':prix', $Appartement->getPrix());
        $q->bindValue(':adresse', $Appartement->getAdresse());
        $q->bindValue(':paiement', $Appartement->getPaiement());
        $q->bindValue(':detail', $Appartement->getDetail());
        $q->bindValue(':auteur', $Appartement->getAuteur());
       

        $q->bindValue(':id', $Appartement->getId(), PDO::PARAM_INT);
        $q->execute();
    }
    public function updatePhoto($photo,$id){
        $q = $this->db->prepare('UPDATE appartement SET photo = :photo WHERE id = :id');
        $q->bindValue(':photo', $photo);
        $q->bindValue(':id', $id );
        $q->execute();
    }

    public function setDb($db){
        $this->db = $db;
    }
}
?>