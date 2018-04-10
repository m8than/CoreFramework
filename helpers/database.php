<?php

class Database
{
    private $_db;
    private $_prefix;
    public function __construct($host, $db, $username, $password, $prefix = "")
    {
        $this->_db = new PDO("mysql:host={$host};dbname={$db}", $username, $password);
        $this->_prefix = $prefix;
    }
    
    public function insert($table, $info)
    {
        $table = $this->_prefix . $table;
        $fields = implode(", ", array_keys($info));
        
        $values = ":".implode(", :", array_keys($info));
        $stmt = $this->_db->prepare("INSERT INTO {$table} ({$fields}) VALUES ({$values})");
        
        foreach($info as $key=>$value)
        {
            $stmt->bindValue(":".$key, $value);
        }
        
        if($stmt->execute())
        {
            return $this->_db->lastInsertId();
        }
        else if($stmt->errorCode() != 0)
        {
            $errors = $stmt->errorInfo();
            return $errors[2];
        }
    }
    public function selectMap($table, $where="", $bind="", $fields="*", $suffix="")
    {
        $results = $this->select($table, $where, $bind, $fields, $suffix);
        if(count($results)<1)
        {
            return 'No results';
        }
        $keys = array_keys($results[0]);
        
        $return = array();
        foreach($results as $result)
        {
            if(count($keys) > 2)
            {
                $row_array = array();
                for($i=1;$i<count($keys);$i++)
                {
                     array_push($row_array, $result[$keys[$i]]);
                     $row_array[$keys[$i]] = $result[$keys[$i]];
                }
                $return[$result[$keys[0]]] = $row_array;
            }
            else
            {
                $return[$result[$keys[0]]] = $result[$keys[1]];
            }
        }
        return $return;
    }
    public function selectCol($table, $where="", $bind="", $field="id", $suffix="")
    {
        $result = $this->select($table, $where, $bind, $field, $suffix);
        return count($result) > 0 ? $result[0][$field] : false;
    }
    public function selectRow($table, $where="", $bind="", $fields="*", $suffix="")
    {
        $result = $this->select($table, $where, $bind, $fields, $suffix);
        return count($result) > 0 ? $result[0] : false;
    }
    public function select($table, $where="", $bind="", $fields="*", $suffix="")
    {
        $table = $this->_prefix . $table;
        
        if($where == "")
            $wherestr = "";
        else
            $wherestr = "WHERE ".$where;
            
        
        $stmt = $this->_db->prepare("SELECT {$fields} FROM {$table} {$wherestr} {$suffix}");
        if($bind != "")
        {
            foreach($bind as $key=>$value)
            {
                $stmt->bindValue($key, $value);
            }
        }
        $results = array();
        if($stmt->execute())
        {
            $i = 0;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $results[$i] = $row;
                $i++;
            }
            return $results;
        }
        else if($stmt->errorCode() != 0)
        {
            $errors = $stmt->errorInfo();
            return $errors[2];
        }
    }
    public function delete($table, $where="", $bind="")
    {
        $table = $this->_prefix . $table;
        
        if($where == "")
            $wherestr = "";
        else
            $wherestr = "WHERE ".$where;
            
        
        $stmt = $this->_db->prepare("DELETE FROM {$table} {$wherestr}");
        if($bind != "")
        {
            foreach($bind as $key=>$value)
            {
                $stmt->bindValue($key, $value);
            }
        }
        $results = array();
        if($stmt->execute())
        {
            $i = 0;
            while ($row = $stmt->fetch())
            {
                $results[$i] = $row;
                $i++;
            }
            return $results;
        }
        else if($stmt->errorCode() != 0)
        {
            $errors = $stmt->errorInfo();
            return $errors[2];
        }
    }
    public function update($table, $info, $where="", $bind="")
    {
        $table = $this->_prefix . $table;
        
        if($where == "")
            $wherestr = "";
        else
            $wherestr = "WHERE ".$where;
        
        $fields = array();
        foreach($info as $key=>$value)
        {
            $fields[] = $key."=:GENERATED".$key;
        }
        $fields = implode(', ', $fields);
        
        $stmt = $this->_db->prepare("UPDATE {$table} SET {$fields} {$wherestr}");
               
        foreach($bind as $key=>$value)
        {
            $stmt->bindValue($key, $value);
        }
        
        foreach($info as $key=>$value)
        {
            $stmt->bindValue(":GENERATED".$key, $value);
        }
        
        if($stmt->execute())
        {
            return $this->_db->lastInsertId();
        }
        else if($stmt->errorCode() != 0)
        {
            $errors = $stmt->errorInfo();
            return $errors[2];
        }
    }
}