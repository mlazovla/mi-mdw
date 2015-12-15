<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 29.10.15
 * Time: 20:59
 */
namespace App\Model;

use Nette, App\Model;

/**
 * Parse SVG
 * http://www.w3schools.com/svg/svg_path.asp
 *
 *
 * This parser enable to read some
 */
class Histogram extends Nette\Object
{
	protected $values;
	protected $min;
	protected $max;
	protected $partCount;

	private $chunksize;

	/**
	 * @param int $partCount
	 * @param float|int $min
	 * @param float|int $max
	 */
	function __construct($partCount, $min = 0, $max = 1)
	{
		$this->values = array();
		for ($i = 0; $i<$partCount; $i++) {
			$this->values[$i] = 0;
		}

		$this->min = $min;
		$this->max = $max;
		$this->partCount = $partCount;
		$this->chunksize = ($max - $min) / $partCount;
	}


	/**
	 * Add number of array of numbers
	 * @param float[] $numbers
	 */
	public function add($numbers)
	{
		if (!is_array($numbers)) {
			$this->addNumber($numbers);
		} else {
			foreach($numbers as $number) {
				$this->addNumber($number);
			}
		}
	}

	/**
	 * Add number to histogram
	 *
	 * @param float|\float[] $number
	 */
	protected function addNumber($number)
	{
		// Number out of range
		if ($number < $this->min || $number > $this->max) {
			return;
		}

		// Select a position of number and incriminate
		$pos = (int)floor(($number - $this->min) / $this->chunksize);
		$pos = max($pos, 0);
		$pos = min($pos, $this->partCount - 1);
		$this->values[$pos]++;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		$ret = '';
		foreach($this->values as $val) {
			$ret .= $val . ',';
		}
		return rtrim($ret, ',');
	}


}
