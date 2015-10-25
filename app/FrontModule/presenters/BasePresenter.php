<?php

namespace App\FrontModule\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{

	protected function createComponentUploadSvgForm() {
		$form = new Nette\Forms\Form();

		$form->addUpload('file', 'SVG file')
			->addRule($form::MAX_FILE_SIZE, 'Your file is too big, max file size is 500kB.', 1024 * 512);
		$form->addSubmit('submit', 'Upload & compare');

		$form->onValidate[] = array($this, 'uploadSvgFormValidate');
		$form->onSuccess[] = array($this, 'uploadSvgFormSucceeded');

		return $form;
	}

	public function uploadSvgFormValidate($form, $values) {



	}

	public function uploadSvgFormSuceeded($form, $values) {
		// use parsed svg for saving to database
		dump('bagr'); exit;
		$svg = new Model\SVGParser($values['file']->getValue());

		$this->redirect('homepage');
	}








}
