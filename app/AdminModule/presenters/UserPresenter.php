<?php
/**
 * Created by PhpStorm.
 * User: vml
 * Date: 05.08.15
 * Time: 22:12
 */

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Users edit presenter.
 */
class UserPresenter extends BasePresenter
{
	/**
	 * @var Model\UserManager
	 * @inject
	 */
	public $item;

	/**
	 * @var Nette\Database\Context
	 * @inject
	 */
	public $database;

	protected $userTable;

	public function startup() {
		parent::startup();
		$this->userTable =  $this->database->table(Model\UserManager::TABLE_NAME);
	}

	public function renderDefault()
	{
		$this->template->item = $this->userTable;
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

		$this['itemForm']->setDefaults($defaults);
	}

	protected function createComponentItemForm()
	{
		$form = new UI\Form;

		$form->addText('email', 'Email:')
			->addRule($form::EMAIL, 'Email není ve správném tvaru.')
			->setRequired();

		$form->addText('password', 'Heslo:')
			->addRule($form::MIN_LENGTH, 'Minimální délka hesla je 6 znaků.', 6);

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'itemFormSucceeded');

		return $form;
	}


	public function itemFormSucceeded(UI\Form $form, $values)
	{
		$itemId = $this->getParameter('id');
		if ($itemId) {
			$item = $this->database->table()->get($itemId);
			if (!$item) {
				$this->error('Data nebyla nalezena v databázi.', 404);
			} else {
				// todo: upravit ucet
			}
			$this->flashMessage('Změny uloženy.', 'success');

		} else {
			try {
				$this->item->add($values['email'], $values['password']);
			} catch (\Exception $e) {
				$this->flashMessage('Účet se nepodařilo vytvořit, nejspíš už existuje se stejným jménem.', 'danger');
			}
			$this->flashMessage('Účet vytvořen.', 'success');
		}
		$this->redirect('default');
	}

	public function actionDelete($id) {
		try {
			if (count($this->userTable) <= 1) {
				throw new \Exception('Not delete last acount.');
			}
			$this->userTable->get($id)->delete();
			$this->flashMessage('Účet odstraněn.', 'success');
		} catch (\Exception $e) {
			$this->flashMessage('Účet se nepodařilo odebrat.', 'danger');
		}
		$this->redirect('default');
	}

	public function actionChangePassword($id) {
		$item = $this->item->get($id);
		if (!$item) {
			$this->error('Data pod ID '. $id .' nebyla nalezena v databázi.', 404);
		}
		// TODO: udělat
		$this->redirect('default');
	}
}
