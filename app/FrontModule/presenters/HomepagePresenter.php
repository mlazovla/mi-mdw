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

		$this->updateSvgSimilarity();

		$this->flashMessage('SVG suceffuly uploaded, starting comparator.', 'success');
		if (isset($lastSvg)) {
			$this->redirect('Svg:show',  ['id' => $lastSvg->id]);
		} else {
			$this->redirect('default');
		}
	}

	public function actionUpdate()
	{
		$this->updateSvgSimilarity(true);
		$this->terminate();
	}

	public function actionRebuild()
	{
		echo ('TRUNCATE SIMILARITY TABLE DATA. <br />');
		$this->svg->truncateSimilarityTable();

		echo ('COMPUTING NEW SIMILARITY... It can take a while.<br />');
		$this->updateSvgSimilarity();

		$this->terminate();
	}

	/**
	 * Update
	 * @param bool $verbose
	 * @return bool
	 */
	private function updateSvgSimilarity($verbose = false)
	{
		if ($verbose) {
			$outputDirection = '2>&1';
		} else {
			$outputDirection = '> /dev/null 2>/dev/null &';
		}
		exec('PYTHONPATH="/Library/Frameworks/Python.framework/Versions/3.5/bin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:/opt/X11/bin:/usr/texbin:/usr/local/sbin"; python /Users/vml/Zend/workspaces/DefaultWorkspace11/mi-vwm/bin/distanceMetric/vmw.py ' . $outputDirection, $out, $status);
		if ($verbose) {
			dump($out);
		}
		return $status == 0;
	}

};
