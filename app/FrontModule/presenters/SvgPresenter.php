<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 04.11.15
 * Time: 19:10
 */

namespace App\FrontModule\Presenters;

use Nette,
	App\Model;

/**
 * Homepage presenter.
 */
class SvgPresenter extends BasePresenter
{
	/**
	 * @var Model\Svg
	 * @inject
	 */
	public $svg;

	public function renderDefault()
	{
		$this->template->svgs = $this->svg->select('id,name')->order('id DESC')->limit(10);
	}

	public function renderShow($id)
	{
		$this->template->svg = $this->svg->get($id);
	}

	public function actionDelete($id)
	{
		$deletedSvg = $this->svg->get($id);
		$name = $deletedSvg->name;
		$deletedSvg->delete();

		$this->flashMessage('SVG "'. $name . '"" was deleted.', 'success');
		$this->redirect('Svg:default');
	}

	public function actionRaw($id)
	{
		$item = $this->svg->get($id);
		echo $item->content;
		$this->terminate();
	}

	public function actionImg($id)
	{
		$item = $this->svg->get($id);
		header('Content-type:image/svg+xml');
		header('Content-Disposition: inline; filename="' . $item->name . '"');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . strlen($item->content));
		header('Accept-Ranges: bytes');

		echo $item->content;
		$this->terminate();
	}
};