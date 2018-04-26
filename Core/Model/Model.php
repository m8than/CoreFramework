<?php
namespace Core\Model;

use Core\Registry\Registry;

class Model
{
    private $_id;
    private $_db;
    private $_data;
    
    private $table;
    private $writeable;
    
    public function toArray()
    {
        return $this->_data;
    }
    public function __construct($id = 0, $table = '', $column = 'id', $include_deleted = false)
    {
        $this->_id = $id;
        $this->_db = Registry::get('db');
        $class_name = basename(str_replace('\\', '/', get_called_class()));
        $this->table = empty($table) ? $class_name . 's' : $table;
        if($id)
        {
            $data = $this->_db->select($this->table, $column.'=:id', array('id' => $id));
            if(count($data) > 0)
            {
                $this->_data = $data[0];
            }
            else
            {
                $this->_data = null;
            }
                      
            $this->_id = $this->_data['id'];
            if(!$include_deleted && $this->get('deleted') != null)
            {
                if($this->get('deleted'))
                {
                    $this->_data = null;
                    $this->_id = 0;
                }
            }
        }
    }
    public function setValues($keyvalue)
    {
        foreach($keyvalue as $key => $value)
        {
            if(!isset($this->writeable) || in_array($key, $this->writeable))
            {
                $this->_data[$key] = $value;
            }
        }
        return $this;
    }
    public function getValues()
    {
        return $this->_data;
    }
    public function set($column, $value)
    {
        if(!isset($this->writeable) || in_array($column, $this->writeable))
        {
            $this->_data[$column] = $value;
        }
        return $this;
    }
    public function get($column)
    {
        if(isset($this->_data[$column]))
        {
            return $this->_data[$column];
        }
        else
        {
            return null;
        }
    }
    public function save()
    {
        if($this->_id == 0)
        {
            return $this->_db->insert($this->table, $this->_data);
        }
        else
        {
            return $this->_db->update($this->table, $this->_data, 'id=:id', array('id' => $this->_id));
        }
    }
    public function delete($hard = false)
    {
        if($this->get('deleted') != null)
        {
            $this->set('deleted', 1);
        }
        if($this->get('deleted_date') != null)
        {
            $this->set('deleted_date', date('Y-m-d H:i:s'));
        }
        if($hard)
        {
            $this->_db->delete($this->table, 'id=:id', array('id'=>$this->_id));
        }
        $this->save();
    }
    public static function create()
    {
        return new static();
    }
    public static function fetch($id, $column = 'id', $include_deleted = false)
    {
        return new static($id, '', $column, $include_deleted);
    }
}
