<?php

class dbGestion
{
    private $dbhost = DB_HOST;
    private  $dbuser = DB_USER;
    private  $dbpass = DB_PASS;
    private $dbname = DB_NAME;
    private $dbTable;
    public  $mysqli;

    public function __construct($dbTable = null)
    {
        $this->dbTable = $dbTable;
        $this->connect();
    }

    /**
     * It connects to the database
     * 
     * @return The mysqli object.
     */
    private function connect()
    {
        $this->mysqli = new  \MySQLi($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        $this->mysqli->set_charset("utf8mb4");
        if ($this->mysqli->connect_errno) {
            printf("Connect failed: %s<br />", $this->mysqli->connect_error);
            exit();
        }
        return $this->mysqli;
    }

    /**
     * It disconnects from the database
     * 
     * @param mysqli The mysqli object that you want to disconnect.
     */
    public function disconnect($mysqli)
    {
        $mysqli->close();
    }

    /**
     * It selects all the rows from the table
     * 
     * @return The result of the query.
     */
    public function selectAll($table)
    {
        $result = $this->mysqli->query("SELECT * FROM " . $table);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * It takes the email and password from the form, and checks if they match a user in the database. If they do, it returns
     * the user's information. If they don't, it returns false
     * 
     * @return An array of the user's information.
     */
    public function login()
    {
        $result = $this->mysqli->query('SELECT * FROM users WHERE email = "' . $_POST['email'] . '" AND password = "' . hash('sha256', $_POST["password"]) . '"');
        return $result->fetch_assoc();
    }

    public function getClientByEmail($email)
    {
        $result = $this->mysqli->query('SELECT * FROM clients WHERE email = ' . $email);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    /**
     * > This function returns a single row from the database table `roles` where the `id` column matches the value of the
     * `` parameter
     * 
     * @param id The id of the role you want to get.
     * 
     * @return An array of the role with the id of .
     */
    public function getRoleById($id)
    {
        $result = $this->mysqli->query('SELECT * FROM roles WHERE id = ' . $id);
        return $result->fetch_assoc();
    }

    /**
     * It returns all the rows from the `meets` table where the `user_id` column matches the `` parameter
     * 
     * @param id The id of the user
     * 
     * @return An array of associative arrays.
     */
    public function getMeetsUserById($id)
    {
        $result = $this->mysqli->query('SELECT * FROM meets WHERE user_id = ' . $id);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * > Get a meet by its id
     * 
     * @param id The id of the meet you want to get.
     * 
     * @return An associative array of the meet with the given id.
     */
    public function getMeetById($id)
    {
        $result = $this->mysqli->query('SELECT * FROM meets WHERE id = ' . $id);
        return $result->fetch_assoc();
    }

    /**
     * It returns all the rows from the `meets` table where the `user_id` is equal to the `` parameter and the `start_date`
     * is greater than or equal to the current date minus 7 days
     * 
     * @param id the user id
     * 
     * @return An array of associative arrays.
     */
    public function getMeetRecentById($id)
    {
        $result = $this->mysqli->query('SELECT * FROM meets WHERE user_id = ' . $id  . ' AND start_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        return  $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * It returns the number of clients that a user has met
     * 
     * @param id the id of the user
     * 
     * @return An array of associative arrays.
     */
    public function getClientByIdUser($id)
    {
        $result = $this->mysqli->query('SELECT DISTINCT c.id AS total FROM clients c INNER JOIN meets m ON c.id = m.client_id WHERE m.user_id = ' . $id);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get the client with the given id from the database.
     * 
     * @param id The id of the client you want to get.
     * 
     * @return An associative array of the client's information.
     */
    public function getClientById($id)
    {
        $result = $this->mysqli->query('SELECT * FROM clients WHERE id = ' . $id);
        return $result->fetch_assoc();
    }

    /**
     * It returns an array of all the agents that are assigned to a manager
     * 
     * @param id The id of the manager
     * 
     * @return An array of associative arrays.
     */
    public function getAgentsByManagerId($id)
    {
        $result = $this->mysqli->query('SELECT * FROM users WHERE manager_user_id = ' . $id);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id)
    {
        $result = $this->mysqli->query('SELECT * FROM users WHERE id = ' . $id);
        return $result->fetch_assoc();
    }

    /**
     * It deletes a record from the database
     * 
     * @param id The id of the record you want to delete.
     */
    public function delete($id)
    {
        $result = $this->mysqli->prepare("DELETE FROM " . $this->dbTable . " WHERE id = ? ");
        $result->bind_param("i", $id);
        $result->execute();
    }
}
