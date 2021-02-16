<?php
class ClientsManager{

    protected $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function add(Clients $client){
        $requete = $this->db->prepare(' INSERT INTO clients SET
        appartement = :appartement,
        nom = :nom,
        prenom = :prenom,
        email = :email
        ');

        $requete->bindValue(':appartement', $client->getAppartement());
        $requete->bindValue(':nom', $client->getNom());
        $requete->bindValue(':prenom', $client->getPrenom());
        $requete->bindValue(':email', $client->getEmail());
  
        $requete->execute();
    }

    public function count(){
        return $this->db->query('SELECT COUNT(*) FROM clients')->fetchColumn();
    }

    public function delete($id){
        $this->db->exec('DELETE FROM clients WHERE id = '. $id);
    }


    public function exists($info){
        $q = $this->db->prepare('SELECT COUNT(*) FROM clients WHERE email = :email');
        $q->execute(array(':email' => $info));
        return (bool) $q->fetchColumn();
    }

    public function getUnique($id){
        $requete = $this->db->prepare('SELECT * FROM clients WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'clients');
        return $requete->fetch();
    }

    public function getUniqueCon($nom,$prenom){
        $requete = $this->db->prepare('SELECT * FROM clients WHERE nom = :nom AND prenom = :prenom');
        $requete->bindValue(':nom', $nom );
        $requete->bindValue(':prenom', $prenom  );
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'clients');
        return $requete->fetch();
    }
    public function getList($debut = 0, $limite = 4){
        $sql = 'SELECT * FROM clients ORDER BY id DESC';
        // On verifie l'integrite des paramètres fournis.
        if ($debut != -1 || $limite != -1){
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }
        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'clients');
        $liste = $requete->fetchAll();
        $requete->closeCursor();
        return $liste;
    }

    public function save(Clients $client){
        if ($client->isValid()){
            $client->isNew() ? $this->add($client) : $this->update($client);
        }
        else{
            throw new RuntimeException('');
        }
    }

    public function update(Clients $client){
        $q = $this->db->prepare('UPDATE clients SET appartement = :appartement ,nom = :nom,prenom = :prenom,email = :email WHERE id = :id');
        $q->bindValue(':nom', $user->getnom());
        $q->bindValue(':prenom', $user->getPrenom());
        $q->bindValue(':email', $user->getEmail());
        $q->bindValue(':appartement', $user->getId(), PDO::PARAM_INT);
        $q->execute();
    }

    public function setDb($db){
        $this->db = $db;
    }
}
?>