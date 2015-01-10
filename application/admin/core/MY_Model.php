<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {

    public $primaryFilter = 'intval'; // htmlentities for string keys

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    /**
     * Get one record, based on ID, or get all records. You can pass a single
     * ID, an array of IDs, or no ID (in which case the  method will return
     * all records)
     *
     * If you request a single ID the result will be returned as an associative array:
     * array('id' => 1, 'title' => 'Some title')
     * In all other cases the result wil be returned as an array of arrays
     * array(array('id' => 1, 'title' => 'Some title'), array('id' => 2, 'title' => 'Some other title'))
     *
     * Thanks to Zack Kitzmiller who suggested some improvements.
     *
     * @param string $table
     * @param string $order_by
     * @param mixed $id An ID or an array of IDs (optional, default = FALSE)
     * @return array
     * @author Joost van Veen
     */
    public function get ($table,$order_by,$ids = FALSE){

        // Set flag - if we passed a single ID we should return a single record
        $single = $ids == FALSE || is_array($ids) ? FALSE : TRUE;
        // Limit results to one or more ids
        if ($ids !== FALSE) {
            // $ids should always be an array
            is_array($ids) || $ids = array($ids);
            // Sanitize ids
            $filter = $this->primaryFilter;
            $ids = array_map($filter, $ids);
            $this->db->where_in($this->primary_key, $ids);
        }
        $this->db->order_by($order_by);
        // Return results
        $single == FALSE || $this->db->limit(1);
        $method = $single ? 'row_array' : 'result_array';
        return $this->db->get($table)->$method();
    }

    /**
     * Get records by one or more keys.
     * @param string $table
     * @param mixed $key can be a string, in which case teh value is in $val. Can also ba a key => value pair array.
     * @param mixed $val The value for a set set $key
     * @param boolean $orwhere
     * @param boolean $single
     * @return void
     * @author Joost van Veen
     */
    public function get_by ($table,$key, $val = FALSE, $orwhere = FALSE, $single = FALSE) {
        // Limit results
        if (! is_array($key)) {
            $this->db->where(htmlentities($key), htmlentities($val));
        }
        else {
            $key = array_map('htmlentities', $key);
            $where_method = $orwhere == TRUE ? 'or_where' : 'where';
            $this->db->$where_method($key);
        }
        // Return results
        $single == FALSE || $this->db->limit(1);
        $method = $single ? 'row_array' : 'result_array';
        return $this->db->get($table)->$method();
    }

    /**
     * Save or update a record.
     * @param string $table
     * @param array $data
     * @param mixed $id Optional
     * @param string $primary_key (optional, default = 'ID')
     * @return mixed The ID of the saved record
     * @author Nguyen Tho Trung
     */
    public function save($table, $data, $id = FALSE,$primary_key ='ID') {
        if ($id == FALSE) {
            // This is an insert
            $this->db->set($data)->insert($table);
        }
        elseif ($id != FALSE && is_array($id)) {
            // This is an update follow multi key
            $filter = $this->primaryFilter;
            $this->db->set($data);
            foreach($id as $key => $value){
                $this->db->where($key, $filter($value));
            }
            $this->db->update($table);
        }
        else {
            // This is an update follow primary key
            $filter = $this->primaryFilter;
            $this->db->set($data)->where($primary_key, $filter($id))->update($table);
        }
        // Return the ID
        return $id == FALSE ? $this->db->insert_id() : $id;
    }
    /**
     * Delete one or more tables by ID
     * @param mixed $tables an table or an array of tables array(table1 =>primary_key1,table2 =>primary_key2)
     * @param mixed $value
     * @param string $primary_key (optional, default = 'ID')
     * @return void
     * @author Nguyen Tho Trung
     */
    function delete($tables, $value, $primary_key ='ID'){
        $tables = ! is_array($tables) ? array($tables => $primary_key) : $tables;
        $filter = $this->primaryFilter;
        $value = $filter($value);
        if ($value) {
                foreach($tables as $table => $primary_key){
                    $this->db->where(htmlentities($primary_key), htmlentities($value))->delete($table);
                }
            }
        return $this->db->affected_rows()>0 ? true : false;
    }
    /**
     * Delete one or more tables by ID
     * @param mixed $table
     * @param mixed $key can be a string, in which case teh value is in $val. Can also ba a key => value pair array.
     * @return bolean
     * @author Nguyen Tho Trung
     */
    function is_double($table,$key)
     {
        $query=$this->db->get_where($table,$key);
        if($query -> num_rows() > 0):
           return TRUE;
        else:
           return FALSE;
        endif;
     }
}