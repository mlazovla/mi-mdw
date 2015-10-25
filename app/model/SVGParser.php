<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 25.10.15
 * Time: 15:26
 */
namespace App\Model;

use Nette, App\Model;

/**
 * Parse SVG
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

	private $svg = '';

	private function init()
	{
		$this->svg = '';
		$this->m = array();
		$this->l = array();
		$this->h = array();
		$this->v = array();
		$this->c = array();
		$this->s = array();
		$this->q = array();
		$this->t = array();
		$this->a = array();
	}

	public function __construct($svgStr = '')
	{
		$this->init();
		$this->svg = $svgStr;

		if (!$svgStr) {
			return;
		}

		$xmlObject = new \SimpleXMLElement($svgStr);

		dump($xmlObject); exit;


		$re = "/<\\s*path[\\w,\\.\\-\\s=\"]*\\/>/";
		$pathes = array();
		if (preg_match($re, $svgStr, $pathes) === false) {
			throw new \InvalidArgumentException('Error during parse path element in SVG file.');
		}

		foreach ($pathes as $path) {

		}

	}
}