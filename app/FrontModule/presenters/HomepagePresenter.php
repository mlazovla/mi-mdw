<?php

namespace App\FrontModule\Presenters;

use Nette,
    App\Model;

/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	protected function createComponentUploadSvgForm()
	{
		$form = new Nette\Application\UI\Form();

		$form->addUpload('file', 'SVG file')
			->addRule($form::MAX_FILE_SIZE, 'Your file is too big, max file size is 500kB.', 1024 * 512);
		$form->addSubmit('submit', 'Upload & compare');

		$form->onSuccess[] = array($this, 'uploadSvgFormSuceeded');

		return $form;
	}

	public function uploadSvgFormSuceeded(Nette\Application\UI\Form $form, $values)
	{
		$filePath = "../www/content/svg/" .time() . ".svg";
		$values['file']->move($filePath);

		$fileContent = file_get_contents($filePath);

		$svg = new Model\SVGParser($fileContent);

		dump($svg); exit;

		$this->redirect('homepage');
	}
}
