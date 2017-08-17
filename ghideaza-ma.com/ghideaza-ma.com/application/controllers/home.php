<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$places = array("Turnul Eiffel", "St Tropez", "Palatul Versailles", "Marele Canion", "Manhattan", "Parcul Yellowstone", "Podul Golden Gate", "Cascada Niagara", "Casa Alba", "Coloseumul Roman", "Turnul inclinat din Pisa", "Cappadocia", "Hierapolis", "The Eden Project", "Insula Skye", "Turnurile Petronas", "Teotihuacan", "Insula Tasmania", "Insula Bali", "Barbados", "Seychelles", "Stanca Preikestolen", "Insula Bora-Bora", "Times Square", "Parcul Disneyland", "Trafalgar Square", "Catedrala Notre Dame", "Marele Zid Chinezesc", "Bazilica Sacre-Coeur", "Muzeul Luvru", "Everland", "Orasul interzis", "Gradinile Tivoli", "Vatican", "Opera din Sydney", "Empire State Building", "The London Eye", "Parcul Yosemite", "Piramidele din Giza", "Pompeii", "Taj Mahal", "Big Ben", "Hawaii", "Cascada Iguazu");
		$slides = array();

		while (count($slides) < 6) {
			$place = $places[rand(0, 43)];

			if (!in_array($place, $slides))
				array_push($slides, $place);
		}

		$this->load->model('SearchDB');
		$lastSearches = $this->SearchDB->getLastSearches(8);

		$slide = array();

		for ($i = 0; $i < 6; $i++) {
			$slide[] = array(
			                 'src' => (site_url()).'res/img/slide/'.(array_search($slides[$i], $places)).'.jpg',
			                 'href' => (site_url('/place/info/')).'/'.(str_replace(' ', '%20', $slides[$i])),
			                 'name' => $slides[$i]);
		}

		$data = array(
		              'slide' => $slide,
		              'last_search' => $lastSearches
		              );

		$this->load->view('pages/home', $data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */