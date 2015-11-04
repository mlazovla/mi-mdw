<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 25.10.15
 * Time: 15:26
 */
namespace App\Model;

use Nette, App\Model;

class SVGPathElementException extends \InvalidArgumentException {

};

/**
 * Parse SVG
 * http://www.w3schools.com/svg/svg_path.asp
 *
 *
 * This parser enable to read some
 */
class SVGParser extends Nette\Object
{


/*
 * M = moveto
 * L = lineto
 * H = horizontal lineto
 * V = vertical lineto
 * C = curveto
 * S = smooth curveto
 * Q = quadratic Bézier curve
 * T = smooth quadratic Bézier curveto
 * A = elliptical Arc
 * Z = closepath
 */

	/**
	 * Moveto - moving the pencil, not write
	 * @var float[]
	 */
	private $m;

	/**
	 * Lineto - write a line
	 * @var float[]
	 */
	private $l;

	/**
	 * Horizontal lineto - write a line
	 * @var float[]
	 */
	private $h;

	/**
	 * Vorizontal lineto - write a line
	 * @var float[]
	 */
	private $v;

	/**
	 * Curveto - write a curve
	 * @var float[]
	 */
	private $c;

	/**
	 * Smooth curveto - write a curve
	 * @var float[]
	 */
	private $s;

	/**
	 * Quadratic Bézier curve - write a curve
	 * @var float[]
	 */
	private $q;

	/**
	 * Smooth quadratic Bézier curveto - write a curve
	 * @var
	 */
	private $t;

	/**
	 * Elliptical Arc
	 * @var
	 */
	private $a;

	private $angles = array();

	private $colors = array();

	private $width = 0.0;

	private $height = 0.0;

	/**
	 * @return \float[]
	 */
	public function getM()
	{
		return $this->m;
	}

	/**
	 * @return \float[]
	 */
	public function getL()
	{
		return $this->l;
	}

	/**
	 * @return \float[]
	 */
	public function getH()
	{
		return $this->h;
	}

	/**
	 * @return \float[]
	 */
	public function getV()
	{
		return $this->v;
	}

	/**
	 * @return \float[]
	 */
	public function getC()
	{
		return $this->c;
	}

	/**
	 * @return \float[]
	 */
	public function getS()
	{
		return $this->s;
	}

	/**
	 * @return \float[]
	 */
	public function getQ()
	{
		return $this->q;
	}

	/**
	 * @return mixed
	 */
	public function getT()
	{
		return $this->t;
	}

	/**
	 * @return mixed
	 */
	public function getA()
	{
		return $this->a;
	}

	/**
	 * @return array
	 */
	public function getColors()
	{
		return $this->colors;
	}

	/**
	 * @return float
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * @return float
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * @return array
	 */
	public function getAngles()
	{
		return $this->angles;
	}


	/* ---------------------------------- */

	/**
	 * Reset class
	 */
	private function init()
	{
		$this->m = array();
		$this->l = array();
		$this->h = array();
		$this->v = array();
		$this->c = array();
		$this->s = array();
		$this->q = array();
		$this->t = array();
		$this->a = array();

		$this->height = 0.0;
		$this->width = 0.0;
		$this->colors = array();
		$this->angles = array();
	}

	/**
	 * @param string $svgStr
	 */
	public function __construct($svgStr)
	{
		$this->init();

		if (!$svgStr) {
			throw new \InvalidArgumentException('SVG Parser: Missing document content.');
		}

		$xmlObject = new \SimpleXMLElement($svgStr);

		// Picture size
		$docSizes = explode(' ', (string)$xmlObject['viewBox']);
		if (count($docSizes) != 4) {
			$this->width = $this->height = 1;
		} else {
			$this->width = $docSizes[2];
			$this->height = $docSizes[3];
		}


		if (! count($xmlObject->g->path)) {
			throw new SVGPathElementException('SVG Parser: do not find Path element in svg.');
		}

		foreach ($xmlObject->g->path as $path) {
			$d = strtolower((string)$path['d']);
			$style = strtolower((string)$path['style']);

			$this->parseStyle($style);


			// Read pathes
			$pathLetters = preg_split('/[\s0-9.,zZ-]*/', $d, -1, PREG_SPLIT_NO_EMPTY);
			$pathNumbers = preg_split('/[a-zA-Z]+\s*/', $d, -1, PREG_SPLIT_NO_EMPTY);

			if (count($pathLetters) != count($pathNumbers)) {
				throw new SVGPathElementException('SVG Parser: Wrong formated path. Missing number values or missing letter functions.');
			}

			foreach($pathNumbers as $k => $numbers) {
				switch($pathLetters[$k]) {
					case 'm':
						$this->parseM($numbers);
						break;
					case 'c':
						$this->parseC($numbers);
						break;
					case 'l':
						$this->parseL($numbers);
						break;
				}
			}

		}
	}

	/**
	 * Normalize pictre canvas size to [0,0 ; 1,1]
	 */
	public function normalize()
	{
		throw new Nette\NotImplementedException("Normalise do not be implemented yet. Is it nessessary?");
	}

	private function parseStyle($style)
	{
		// Read colors
		$colors = array();
		preg_match("/#[0-9a-f]{6}/", $style, $colors);
		foreach($colors as $color) {
			$rgb = $this->readColor($color);
			if ($rgb['r'] != -1) {
				$this->colors[] = $rgb;
			}
		}
	}


	/**
	 * Parse Moveto numbers
	 * @param string $m
	 */
	private function parseM($m)
	{
		$numbers = explode(',', $m);
		if (count($numbers) == 2) {
			$this->m[] = $numbers;
		}
	}

	/**
	 * Parse CurveTo numbers
	 * @param string $c
	 */
	private function parseC($c)
	{
		$pairs = explode(' ', $c);
		$pointBefore = [null, null];
		$pointBeforeBefore = [null, null];

		$counter = 0;

		foreach ($pairs as $pair) {
			$numbers = explode(',', $pair);

			if (count($numbers) != 2) continue;
			$this->c[] = [$numbers[0], $numbers[1]];

			if ($counter >= 2) {
				$this->angles[] = $this->computeAngel(
					$numbers[0], $numbers[1],
					$pointBefore[0], $pointBefore[1],
					$pointBeforeBefore[0], $pointBeforeBefore[1]
				);
			}
			$pointBeforeBefore = $pointBefore;
			$pointBefore = [$numbers[0], $numbers[1]];
			$counter++;
		}
	}

	/**
	 * Parse CurveTo numbers
	 * @param string $l
	 */
	private function parseL($l)
	{
		$pairs = explode(' ', $l);
		$pointBefore = [null, null];
		$pointBeforeBefore = [null, null];

		$counter = 0;

		foreach ($pairs as $pair) {
			$numbers = explode(',', $pair);

			if (count($numbers) != 2) continue;
			$this->l[] = [$numbers[0], $numbers[1]];

			if ($counter >= 2) {
				$this->angles[] = $this->computeAngel(
					$numbers[0], $numbers[1],
					$pointBefore[0], $pointBefore[1],
					$pointBeforeBefore[0], $pointBeforeBefore[1]
				);
			}
			$pointBeforeBefore = $pointBefore;
			$pointBefore = [$numbers[0], $numbers[1]];
			$counter++;
		}
	}


	/**
	 * Compute value of angle AVB in radians
	 *
	 * @param float $ax
	 * @param float $ay
	 * @param float $vx
	 * @param float $vy
	 * @param float $bx
	 * @param float $by
	 * @return float
	 */
	private static function computeAngel($ax, $ay, $vx, $vy, $bx, $by) {
		$vec_va_x = $ax - $vx;
		$vec_va_y = $ay - $vy;
		$vec_vb_x = $bx - $vx;
		$vec_vb_y = $by - $vy;
		$size_va = sqrt($vec_va_x*$vec_va_x + $vec_va_y*$vec_va_y);
		$size_vb = sqrt($vec_vb_x*$vec_vb_x + $vec_vb_y*$vec_vb_y);
		$dotProduct = $vec_va_x*$vec_vb_x + $vec_va_y*$vec_vb_y;
		return (($size_va * $size_vb) == 0) ? 0 : acos($dotProduct / ($size_va * $size_vb));
	}

	/**
	 * Read color in format #RRGGBB (Hexadecimal) and return array with colors in decimal values
	 *
	 * @param $color
	 * @return array
	 */
	private static function readColor($color)
	{
		if (strlen($color) < 7 || substr($color, 0, 1) != '#') {
			return ['r' => -1, 'g' => -1, 'b' => -1];
		}

		return [
			'r' => hexdec(substr($color, 1, 2)),
			'g' => hexdec(substr($color, 3, 2)),
			'b' => hexdec(substr($color, 5, 2)),
		];
	}
};
