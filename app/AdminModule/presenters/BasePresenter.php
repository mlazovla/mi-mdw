<?php

namespace App\AdminModule\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	public function beforeRender()
	{
		$this->template->loggedIn = $this->getUser()->isLoggedIn();
		$this->template->nuser = $this->user;

		if ($this->user->getIdentity()) {
			$this->template->nuserEmail = $this->user->getIdentity()->data['email'];
		} else {
			$this->template->nuserEmail = '';
		}
		$user = $this->getUser();
		if ($this->name != 'Admin:Sign') {
			if (!$user->isLoggedIn()) {
				if ($user->getLogoutReason() === Nette\Security\User::INACTIVITY) {
					$this->flashMessage('Uplynula doba neaktivity! Systém vás z bezpečnostních důvodů odhlásil.', 'warning');
				}
				$this->redirect('Sign:in');
			} else {
				// Reseni acl, zatim staci jen prihlaseny uzivatel
				/*if (!$user->isAllowed($this->reflection->name, $this->getAction())) {
					$this->flashMessage('Na vstup do této sekce nemáte dostatečné oprávnění!', 'warning');
					$this->redirect('Sign:in');
				}*/

			}
		}
	}

}
