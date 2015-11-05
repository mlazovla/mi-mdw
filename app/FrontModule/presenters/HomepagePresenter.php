<?php

namespace App\FrontModule\Presenters;

	use Nette,
		App\Model;

	/**
	 * Homepage presenter.
	 */
class HomepagePresenter extends BasePresenter
{
	/**
	 * @var Model\Svg
	 * @inject
	 */
	public $svg;

	protected function createComponentUploadSvgForm()
	{
		$form = new Nette\Application\UI\Form();

		$form->addUpload('file', 'SVG file')
			->addRule($form::FILLED)
			->addRule($form::MAX_FILE_SIZE, 'Your file is too big, max file size is 500kB.', 1024 * 512);
		$form->addSubmit('submit', 'Upload & compare');

		$form->onSuccess[] = array($this, 'uploadSvgFormSuceeded');

		return $form;
	}

	public function uploadSvgFormSuceeded(Nette\Application\UI\Form $form, $values)
	{
		$filePath = "../www/content/svg/" . time() . ".svg";
		$values['file']->move($filePath);

		$fileContent = file_get_contents($filePath);
		try {
			// Parse SVG
			$svgParsed = new Model\SVGParser($fileContent);

			// Create Histograms
			$histAngle = new Model\Histogram(30, 0, 3.1415);
			$histColorH = new Model\Histogram(8, 0, 1);
			$histColorS = new Model\Histogram(8, 0, 1);
			$histColorL = new Model\Histogram(8, 0, 1);

			// Fill Histograms
			$histAngle->add($svgParsed->getAngles());
			foreach($svgParsed->getColors() as $color) {
				$histColorH;
			}

			// Persist
			$lastSvg = $this->svg->insert(
				array(
					'name' => $values['file']->getSanitizedName(),
					'content' => $fileContent,
					'h_angle' => (string)$histAngle
				)
			);
		} catch (\Exception $ex) {
			$this->flashMessage('SVG cannot be compared. ' . $ex->getMessage(), 'danger');
			$this->redirect('default');
		}

		$this->flashMessage('SVG suceffuly uploaded.', 'success');
		if (isset($lastSvg)) {
			$this->redirect('Svg:show',  ['id' => $lastSvg->id]);
		} else {
			$this->redirect('default');
		}
	}
};
