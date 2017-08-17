<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	public function storeSearch()
	{
		$this->load->model('SearchDB');
		$this->SearchDB->storeSearch($this->uri->segment(3), $this->input->post('pic'), $this->input->post('place'));
	}
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */