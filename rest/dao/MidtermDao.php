<?php
require_once "BaseDao.php";

class MidtermDao extends BaseDao {

    public function __construct(){
        parent::__construct('email');
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
    * Implement DAO method to validate email format and check if email exists
    */
    public function investor_email($email) {
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['status' => 'invalid_format'];
        }

        // Check if email exists in the database
        $query = "SELECT first_name, last_name FROM investors WHERE email = :email";
        $statement = $this->connection->prepare($query);
        $statement->execute([':email' => $email]);
        $investor = $statement->fetch();

        if ($investor) {
            return [
                'status' => 'exists',
                'first_name' => $investor['first_name'],
                'last_name' => $investor['last_name']
            ];
        } else {
            return ['status' => 'not_exists'];
        }
    }

    /** TODO
    * Implement DAO method to return list of investors according to instruction in MidtermRoutes.php
    */
    public function investors(){
        $query = "SELECT * FROM investors";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll();
    }

}
?>
