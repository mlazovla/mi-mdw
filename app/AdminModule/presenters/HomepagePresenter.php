<?php

namespace App\AdminModule\Presenters;

use Nette,
    App\Model;

use App\Model\Category;
use Nette\Database\Context;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	public function actionDefault() {
		if (! $this->user->loggedIn) {
			$this->redirect('Sign:in');
		}
	}

	public function renderDefault()
	{
		// DASHBOARD
	}

}
