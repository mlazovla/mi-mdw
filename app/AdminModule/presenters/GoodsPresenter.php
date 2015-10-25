<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Article edit presenter.
 */
class GoodsPresenter extends BasePresenter
{
	const PAGE_LIMIT = 50;

	/**
	 * @var Model\Goods
	 * @inject
	 */
	public $item;

	/**
	 * @var Model\Category
	 * @inject
	 */
	public $category;

	/**
	 * @var Model\Manufacturer
	 * @inject
	 */
	public $manufacturer;

	/**
	 * @persist
	 */
	public $page;

	public function actionDefault($page = 0) {
		if (!$this->page || $page) {
			$this->page = $page;
		}
	}

	public function renderDefault()
	{
		$this->template->item = $this->item->order('manufacturer.name, name')->limit(self::PAGE_LIMIT, $this->page * self::PAGE_LIMIT);
		$this->template->page = $this->page;
	}

	/**
	 * @param $id
	 */
	public function actionEdit($id) {
		$item = $this->item->get($id);
		if(!$item) {
			$this->error('Data nebyla nalezena v databázi.', 404);
		}
		$defaults = $item->toArray();
		if (!$defaults['manufacturer_id']) {
			$defaults['manufacturer_id'] = '';
		}
		if (!$defaults['category_id']) {
			$defaults['category_id'] = '';
		}
		$this['itemForm']->setDefaults($defaults);
	}

	protected function createComponentItemForm()
	{
		$form = new UI\Form;

		$form->addText('name', 'Název výrobku:')
			->setRequired();

		$categories = $this->category->order('name')->fetchPairs('id', 'name');
		$categories[null] = '--- Žádná kategorie ---';

		$manufacturers = $this->manufacturer->order('name')->fetchPairs('id', 'name');
		$manufacturers[null] = '--- Žádný výrobce ---';

		$form->addSelect('category_id', 'Kategorie', $categories)->setDefaultValue('');

		$form->addSelect('manufacturer_id', 'Výrobce', $manufacturers)->setDefaultValue('');

		$form->addText('stock', 'Kusů skladem')->setType('number')->setDefaultValue(0)
			->addRule($form::RANGE, 'Zadejte celé kladné číslo nebo nulu.', array(0, 1000000000));

		$form->addCheckbox('recommended', 'Je zboží doporučené?');

		$form->addTextArea('description', 'Popis:')
			->setAttribute('class', 'tinyMCE');

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'itemFormSucceeded');

		return $form;
	}


	public function itemFormSucceeded(UI\Form $form, $values)
	{
		if (isset($values['category_id']) && !$values['category_id'] ) {
			$values['category_id'] = $values['category_id'] ? $values['category_id'] : null;
		}
		if (isset($values['manufacturer_id']) && !$values['manufacturer_id'] ) {
			$values['manufacturer_id'] = $values['manufacturer_id'] ? $values['manufacturer_id'] : null;
		}
		$itemId = $this->getParameter('id');
		if ($itemId) {
			$item = $this->item->get($itemId);
			if (!$item) {
				$this->error('Data nebyla nalezena v databázi.', 404);
			} else {
				$item->update($values);
			}
			$this->flashMessage('Změny uloženy.', 'success');

		} else {
			$item = $this->item->insert($values);
			$this->flashMessage('Výrobek vložen do databáze.', 'success');
		}
		$this->redirect('edit', $item->id);
	}

	public function actionDelete($id) {
		try {
			$this->item->get($id)->delete();
			$this->flashMessage('Výrobek odstraněn.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Výrobek se nepodařilo odebrat.', 'danger');
		}
		$this->redirect('default');
	}

	public function actionToggleRecommended($id) {
		$item = $this->item->get($id);
		if (!$item) {
			$this->error('Data pod ID '. $id .' nebyla nalezena v databázi.', 404);
		}
		$item->update(array('recommended' => !$item->recommended));
		$this->flashMessage('Doporučení výrobku '. $item->name ." bylo změněno.", 'success');
		$this->redirect('default');
	}
}
