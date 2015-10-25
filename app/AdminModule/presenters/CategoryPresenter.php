<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Category edit presenter.
 */
class CategoryPresenter extends BasePresenter
{
	/**
	 * @var Model\Category
	 * @inject
	 */
	public $category;

	public function actionDefault() {
	}

	public function renderDefault()
	{
		$this->template->category = $this->category;
	}

	/**
	 * @param $id
	 */
	public function actionEdit($id) {
		$category = $this->category->get($id);
		if(!$category) {
			$this->error('Data nebyla nalezena v databázi.', 404);
		}

		$defaults = $category->toArray();
		if (!$defaults['parent']) {
			$defaults['parent'] = '';
		}

		$this['categoryForm']->setDefaults($defaults);
	}

	protected function createComponentCategoryForm()
	{
		$form = new UI\Form;

		$form->addText('name', 'Název kategorie:')
			->setRequired();

		$category = clone $this->category;
		$categories = $category->where('parent', null)->fetchPairs('id', 'name');
		$categories[null] = '--- Žádná kategorie ---';

		$form->addSelect('parent', 'Nadřazená kategorie', $categories)->setDefaultValue('');

		$form->addCheckbox('visible', 'Zobrazit na webu:')->setDefaultValue(true);


		$form->addTextArea('description', 'Popis:')
			->setAttribute('class', 'tinyMCE');

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'categoryFormSucceeded');

		return $form;
	}


	public function categoryFormSucceeded(UI\Form $form, $values)
	{
		$categoryId = $this->getParameter('id');
		if (isset($values['parent']) && !$values['parent'] ) {
			$values['parent'] = $values['parent'] ? $values['parent'] : null;
		}
		if ($categoryId) {
			$category = $this->category->get($categoryId);
			if (!$category) {
				$this->error('Data nebyla nalezena v databázi.', '404');
			} else {
				$category->update($values);
			}
			$this->flashMessage('Změny uloženy.', 'success');

		} else {
			$category = $this->category->insert($values);
			$this->flashMessage('Kategorie vložena do databáze.', 'success');
		}
		$this->redirect('edit', $category->id);
	}

	public function actionDelete($id)
	{
		try {
			$this->category->get($id)->delete();
			$this->flashMessage('Kategorie odstraněna.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Kategorii se nepodařilo smazat, pravděpodobně máte na webu vystavené výrovky z této kategorie.', 'danger');
		}
		$this->redirect('default');
	}

	public function renderChildren($id)
	{
		$this->template->selectedCategory = $this->category->get($id);
		$this->template->category = $this->category->where('parent', $id);
	}
}
