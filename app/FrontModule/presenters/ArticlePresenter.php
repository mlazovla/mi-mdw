<?php

namespace App\FrontModule\Presenters;

use Nette,
    App\Model;

use App\Model\Article;
use App\Model\Category;
use Nette\Database\Context;

/**
 * Homepage presenter.
 */
class ArticlePresenter extends BasePresenter
{
	/**
	 * @var Model\Article
	 * @inject
	 */
	public $article;

	public function renderShow($id) {
		$this->template->article = $this->article->get($id);
	}

}
