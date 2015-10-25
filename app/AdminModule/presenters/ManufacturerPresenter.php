<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Article edit presenter.
 */
class ManufacturerPresenter extends BasePresenter
{
	/**
	 * @var Model\Manufacturer
	 * @inject
	 */
	public $manufacturer;

	public function actionDefault() {
	}

	public function renderDefault()
	{
		$this->template->manufacturer = $this->manufacturer;
	}

	/**
	 * @param $id
	 */
	public function actionEdit($id) {
		$manufacturer = $this->manufacturer->get($id);
		if(!$manufacturer) {
			$this->error('Data nebyla nalezena v databázi.', 404);
		}
		$this['manufacturerForm']->setDefaults($manufacturer->toArray());
	}

	protected function createComponentManufacturerForm()
	{
		$form = new UI\Form;

		$form->addText('name', 'Název výrobce:')
			->setRequired();

		$form->addTextArea('description', 'Popis:')
			->setAttribute('class', 'tinyMCE');

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'manufacturerFormSucceeded');

		return $form;
	}


	public function manufacturerFormSucceeded(UI\Form $form, $values)
	{
		$manufacturerId = $this->getParameter('id');
		if ($manufacturerId) {
			$manufacturer = $this->manufacturer->get($manufacturerId);
			if (!$manufacturer) {
				$this->error('Data nebyla nalezena v databázi.', '404');
			} else {
				$manufacturer->update($values);
			}
			$this->flashMessage('Změny uloženy.', 'success');

		} else {
			$manufacturer = $this->manufacturer->insert($values);
			$this->flashMessage('Výrobce vložen do databáze.', 'success');
		}
		$this->redirect('edit', $manufacturer->id);
	}

	public function actionDelete($id) {
		try {
			$this->manufacturer->get($id)->delete();
			$this->flashMessage('Výrobce odstraněn.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Výrobce se nepodařilo smazat, pravděpodobně máte na webu vystavené jeho výrobky.', 'danger');
		}
		$this->redirect('default');
	}
}
