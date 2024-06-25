<?php
require_once "BaseDao.php";

class FinalDao extends BaseDao {
    protected $connection;
    public function __construct(){
        parent::__construct('users');
    }

    /** TODO
    * Implement DAO method used to login user
    */
    public function login($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
    
    public function users(){
        $query = "SELECT * FROM users";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /** TODO
    * Implement DAO method used add new investor to investor table and cap-table
    */
    public function investor($investor){
        try {
            // Begin a transaction
            $this->connection->beginTransaction();
    
            // Insert into investors table
            $query1 = "INSERT INTO investors (first_name, last_name, email, company)
                       VALUES (:first_name, :last_name, :email, :company)";
            $statement1 = $this->connection->prepare($query1);
            $statement1->bindParam(':first_name', $investor['first_name']);
            $statement1->bindParam(':last_name', $investor['last_name']);
            $statement1->bindParam(':email', $investor['email']);
            $statement1->bindParam(':company', $investor['company']);
            $statement1->execute();
            
            // Get the last inserted ID from the investors table
            $investor_id = $this->connection->lastInsertId();
    
            // Insert into cap_table
            $query2 = "INSERT INTO cap_table (investor_id, diluted_shares)
                       VALUES (:investor_id, :diluted_shares)";
            $statement2 = $this->connection->prepare($query2);
            $statement2->bindParam(':investor_id', $investor_id);
            $statement2->bindParam(':diluted_shares', $investor['diluted_shares']);
            $statement2->execute();
    
            // Commit the transaction
            $this->connection->commit();
    
            return $investor_id;
        } catch (PDOException $e) {
            // Rollback the transaction in case of an error
            $this->connection->rollBack();
            throw $e;
        }
    }

    /** TODO
    * Implement DAO method to return list of all share classes from share_classes table
    */
    public function share_classes(){
        $query = "SELECT * FROM share_classes";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    /** TODO
    * Implement DAO method to return list of all share class categories from share_class_categories table
    */
    public function share_class_categories(){
        $query = "SELECT * FROM share_class_categories";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
