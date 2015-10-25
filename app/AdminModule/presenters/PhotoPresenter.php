<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Photo edit presenter.
 */
class PhotoPresenter extends BasePresenter
{

	/**
	 * @var Model\Photo
	 * @inject
	 */
	public $item;

	/**
	 * @var Model\Goods
	 * @inject
	 */
	public $goods;


	public function renderDefault($goodsId)
	{
		$goods = $this->goods->get($goodsId);
		if(!$goods) {
			$this->error('Výrobek nebyl nalezen v databázi.', 404);
		}
		$this->template->goods = $goods;
		$this->template->item = $this->item->where('goods_id = ?', $goodsId);
	}

	/**
	 * @param $goodsId
	 * @throws Nette\Application\BadRequestException
	 * @internal param $id
	 */
	public function actionCreate($goodsId) {
		$goods = $this->goods->get($goodsId);
		if(!$goods) {
			$this->error('Výrobek, k kterému nahráváte fotografie, nebyl nalezen v databázi.', 404);
		}
		$this->template->goods = $goods;
	}

	protected function createComponentItemForm()
	{
		$form = new UI\Form;

		$form->addText('name', 'Název fotografie:');

		$form->addTextArea('description', 'Popis:')
			->setAttribute('class', 'tinyMCE');

		$form->addMultiUpload('photo', 'Fotografie JPG')
			->setRequired('Vyberte fotografie.');

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'itemFormSucceeded');

		return $form;
	}

	public function itemFormSucceeded(UI\Form $form, $values)
	{
		$goodsId = $this->getParameter('goodsId');
		foreach ($values['photo'] as $file)
		{
			if (!$this->item->insertPhoto($file, $goodsId, $values['name'], $values['description']))
				$this->flashMessage('Nepodařilo se uložit fotografii: ' . $file->getName(), 'danger');
		}

		$this->flashMessage('Fotografie byly zpracovány.', 'success');
		$this->redirect('Photo:default', $goodsId);
	}


	public function actionDelete($id, $goodsId) {
		if ($this->item->deletePhoto($id)) {
			$this->flashMessage('Fotka byla smazána.', 'success');
		} else {
			$this->flashMessage('Smazání se nezdařilo.', 'danger');
		}
		$this->redirect('Photo:default', $goodsId);
	}
}
