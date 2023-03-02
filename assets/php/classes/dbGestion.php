<?php

class dbGestion
{
    private $dbhost = DB_HOST;
    private  $dbuser = DB_USER;
    private  $dbpass = DB_PASS;
    private $dbTable;
    private $dbname;
    public  $mysqli;

    public function __construct($dbname, $dbTable)
    {
        $this->dbTable = $dbTable;
        $this->dbname = $dbname;
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
    private function disconnect($mysqli)
    {
        $mysqli->close();
    }

    /**
     * It selects all the rows from the table
     * 
     * @return The result of the query.
     */
    public function selectAll()
    {
        $result = $this->mysqli->prepare("SELECT * FROM " . $this->dbTable);
        $this->disconnect($this->mysqli);
        return $result;
    }

    public function select($select, $condition)
    {
        $key = array_keys($condition);
        $value = array_values($condition);
        $string = "";
        for ($i = 1; $i < count($condition); $i++) {
            $string .= $key[$i] . "= ?";
            if ($i !== count($condition) - 1) {
                $string .= " AND";
            }
        }
        $result = $this->mysqli->prepare("SELECT " . $select . " FROM " . $this->dbTable . " WHERE " . $string);
        $result->bind_param(str_repeat('s', count($value)), ...$value);
        $result->execute();
        $this->disconnect($this->mysqli);
        return $result;
    }

    /**
     * It deletes a record from the database
     * 
     * @param id The id of the record you want to delete.
     */
    public function delete($id)
    {
        $this->mysqli->execute("DELETE FROM " . $this->dbTable . " WHERE id = ? ");
        $this->mysqli->bind_param("i", $id);
        $this->disconnect($this->mysqli);
    }

    /**
     * It takes an array of key value pairs, and updates the database with the values
     * 
     * @param array The array of data to be updated.
     */
    public function update($array)
    {
        $key = array_keys($array);
        $value = array_values($array);
        $string = "";
        for ($i = 1; $i < count($array); $i++) {
            $string .= $key[$i] . "= ?";
            if ($i !== count($array) - 1) {
                $string .= ", ";
            }
        }
        $result = $this->mysqli->prepare("UPDATE " . $this->dbTable . " SET " . $string . " WHERE id = " . $value[0]);
        $result->bind_param(str_repeat('s', count($value)), ...$value);
        $result->execute();
        $this->disconnect($this->mysqli);
    }

    /**
     * It takes an array of values, and inserts them into the database
     * 
     * @param array The array of values to be inserted into the database.
     */
    public function insert($array)
    {
        $values = "";
        foreach ($array as $key => $value) {
            $values .= "?";
            if ($key !== array_key_last($array)) {
                $values .= ", ";
            }
        }
        $result = $this->mysqli->prepare("INSERT INTO " . $this->dbTable . "(" . implode(', ', array_keys($array))  . ") VALUES(" . $values . ")");
        $result->bind_param(str_repeat('s', count($array)), ...array_values($array));
        $result->execute();
        $this->disconnect($this->mysqli);
    }
}
