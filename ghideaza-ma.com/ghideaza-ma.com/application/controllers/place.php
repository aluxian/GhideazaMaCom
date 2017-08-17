<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Place extends CI_Controller {

	public function info()
	{
		$this->load->view('pages/place', array('query' => urldecode($this->uri->segment(3)) ));
	}

	public function wiki()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://'.($this->uri->segment(4)).'.wikipedia.org/wiki/'.($this->uri->segment(5)));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
		$result = curl_exec($ch);
		curl_close($ch);

		$result = $this->_getStringBetween($result, '<!-- bodycontent -->', '<!-- /bodycontent -->');

		if ($this->uri->segment(3) == 'full')
			$result = substr($result,  strpos($result, '<table id="toc" class="toc">'));
		else
			$result = substr($result, 0, strpos($result, '<table id="toc" class="toc">'));

		$result = mb_convert_encoding($result, 'utf-8', mb_detect_encoding($result));
		$result = mb_convert_encoding($result, 'html-entities', 'utf-8');

		$doc = new DOMDocument();
		$doc->validateOnParse = true;
		@$doc->loadHTML($result);

		$finder = new DomXPath($doc);
		$removeClasses = array('dezambiguizare', 'infocaseta', 'toclimit-2', 'editsection', 'navbox', 'noprint', 'magnify', 'rellink', 'boilerplate', 'seealso', 'metadata', 'ambox', 'dablink', 'infobox', 'mw-editsection');

		foreach ($removeClasses as $class) {
			foreach ($finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]") as $node)
				$node->parentNode->removeChild($node);
		}

		foreach ($doc->getElementsByTagName('p') as $node) {
			if (strlen(trim($node->nodeValue)) == 0)
				$node->parentNode->removeChild($node);
		}

		foreach ($doc->getElementsByTagName('a') as $node) {
			$val = $node->nodeValue;
			$href = $node->getAttribute('href');

			if (strpos($node->getAttribute('href'), '/w/index.php') === 0)
				$node->parentNode->replaceChild($doc->createElement('span', $val), $node);
			else if (strpos($node->getAttribute('href'), '/wiki') === 0)
				$node->setAttribute('href', 'http://'.($this->uri->segment(4)).'.wikipedia.org'.$href);
		}

		foreach ($doc->getElementsByTagName('ul') as $node) {
			if (strpos($node->childNodes->item(0)->getAttribute('class'), 'gallerybox') === false) {
				if ($node->parentNode->tagName == 'li')
					$node->setAttribute("class", "dash");
				else
					$node->setAttribute("class", "circle");
			}
		}

		echo preg_replace('/^<!DOCTYPE.+?>/', '', str_replace(array('<html>', '</html>', '<body>', '</body>'), array('', '', '', ''), $doc->saveHTML()));
	}

	private function _getStringBetween($string, $start, $end) {
		$string = " ".$string;
		$ini = strpos($string,$start);
		if ($ini == 0) return "";
		return substr($string, $ini + strlen($start), strpos($string,$end,$ini) - $ini);
	}

}

/* End of file place.php */
/* Location: ./application/controllers/place.php */