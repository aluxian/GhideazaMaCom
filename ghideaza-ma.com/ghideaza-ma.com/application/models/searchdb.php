<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SearchDB extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    function getLastSearches($no)
    {
        $this->db->order_by('timestamp', 'desc');
        return $this->db->get_where('search', array(), $no)->result_array();
    }

    function storeSearch($query, $pic, $place)
    {
        $this->db->order_by('timestamp', 'desc');
        $results = $this->db->get_where('search', array(), 1)->result_array();

        if ($results[0]['query'] != $query && strlen($query) > 2) {
            $this->db->where('query', $query);
            $this->db->insert('search', array('query' => $query, 'photo' => $pic, 'place' => $place));
        }
    }

}

/* End of file cache.php */
/* Location: ./application/models/cache.php */