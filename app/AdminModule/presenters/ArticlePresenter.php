<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;

use App\Model\Category;
use Nette\Database\Context;
use Nette\Application\UI;


/**
 * Article edit presenter.
 */
class ArticlePresenter extends BasePresenter
{
	/**
	 * @var Model\Article
	 * @inject
	 */
	public $article;

	public function actionDefault() {
	}

	public function renderDefault()
	{
		$this->template->article = $this->article;
	}

	/**
	 * @param $id
	 */
	public function actionEdit($id) {
		$article = $this->article->get($id);
		if(!$article) {
			$this->error('Data nebyla nalezena v databázi.', 404);
		}
		$this['articleForm']->setDefaults($article->toArray());
	}

	protected function createComponentArticleForm()
	{
		$form = new UI\Form;

		$form->addText('name', 'Název článku:')
			->setRequired();

		$form->addText('menu', 'Položka menu:');

		$form->addText('url', 'Odkaz jinam než na svůj')
			->setType('url');

		$form->addTextArea('annotation', 'Annotace:')
			->setAttribute('class', 'tinyMCE');

		$form->addTextArea('text', 'Článek:')
			->setAttribute('class', 'tinyMCE');

		$form->addSubmit('save', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$form->onSuccess[] = array($this, 'articleFormSucceeded');

		return $form;
	}


	public function articleFormSucceeded(UI\Form $form, $values)
	{
		$articleId = $this->getParameter('id');
		if ($articleId) {
			$article = $this->article->get($articleId);
			if (!$article) {
				$this->error('Data nebyla nalezena v databázi.', '404');
			} else {
				$article->update($values);
			}
			$this->flashMessage('Změny uloženy.', 'success');

		} else {
			$values['user_id'] = $this->user->id;
			$article = $this->article->insert($values);
			$this->flashMessage('Článek vložen do databáze.', 'success');
		}
		$this->redirect('edit', $article->id);
	}

	public function actionDelete($id) {
		$this->article->get($id)->delete();
		$this->flashMessage('Článek odstraněn.', 'success');
		$this->redirect('default');
	}
}
