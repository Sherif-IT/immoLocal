<?php
class UsersManager{

    protected $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function add(Users $user){
        $requete = $this->db->prepare(' INSERT INTO users SET
        login = :login,
        password = :password,
        type = :type,
        auteur = :auteur');
      
        $requete->bindValue(':login', $user->getLogin());
        $requete->bindValue(':password', $user->getPassword());
        $requete->bindValue(':type', $user->getType());
        $requete->bindValue(':auteur', $user->getAuteur());
        
        
        
        $requete->execute();
    }

    public function count(){
        return $this->db->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }

    public function delete($id){
        $this->db->exec('DELETE FROM users WHERE id = '. $id);
    }


    public function exists($info){
        $q = $this->db->prepare('SELECT COUNT(*) FROM users WHERE type = :type');
        $q->execute(array(':type' => $info));
        return (bool) $q->fetchColumn();
    }

    public function getUnique($id){
        $requete = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $requete->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'users');
        return $requete->fetch();
    }
    public function getUniqueCon($login,$password){
        $requete = $this->db->prepare('SELECT * FROM users WHERE login = :login AND password = :password');
        $requete->bindValue(':login', $login );
        $requete->bindValue(':password', $password  );
        $requete->execute();
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Users');
        return $requete->fetch();
    }
    public function getList($debut = 0, $limite = 4){
        $sql = 'SELECT * FROM users ORDER BY id DESC';
        // On vérifie l'intégrité des paramètres fournis.
        if ($debut != -1 || $limite != -1){
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }
        $requete = $this->db->query($sql);
        $requete->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'users');
        $liste = $requete->fetchAll();
        $requete->closeCursor();
        return $liste;
    }

    public function save(Users $user){
        if ($user->isValid()){
            $user->isNew() ? $this->add($user) : $this->update($user);
        }
        else{
            throw new RuntimeException('');
        }
    }

    public function update(Users $user){
        $q = $this->db->prepare('UPDATE users SET login = :login,password = :password,type = :type,auteur = :auteur WHERE id = :id');
        $q->bindValue(':login', $user->getLogin());
        $q->bindValue(':password', $user->getPassword());
        $q->bindValue(':type', $user->getType());
        $q->bindValue(':id', $user->getId(), PDO::PARAM_INT);
        $q->bindValue(':auteur', $user->getAuteur());
        $q->execute();
    }

    public function setDb($db){
        $this->db = $db;
    }
}
?>