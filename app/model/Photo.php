<?php

namespace App\Model;
use Nette\Http\FileUpload;
use Tester\Environment;

/**
 * Persistent object Photo.
 */
class Photo extends \Nette\Database\Table\Selection {

	const SAVE_DIR = "../www/content/photo/";
	const ENABLED_EXTENSION = "jpg jpeg";

    private $db;
    private $table = "photo";

	public function __construct(\Nette\Database\Context $database) {
		parent::__construct($database, $database->getConventions(), $this->table);
		$this->db = $database;
	}

	/**
	 * Save file into filesystem and insert information into database.
	 * Checks extension and gives file mime type.
	 * @param FileUpload $file
	 * @param $goods_id
	 * @param string $description
	 * @return bool success
	 * @throws \Exception
	 */
	public function insertPhoto($file, $goods_id, $name, $description = null) {

		$photo = new Photo($this->db);
		$filename = $file->getName();
		$ext = self::getExtensionByName($filename);

		// povolene pripony
		$enabledExt = explode(' ', self::ENABLED_EXTENSION);
		if (!in_array($ext, $enabledExt)) {
			return false;
		}

		$lastPhoto_id = $photo->insert(array(
			'name' => $name,
			'goods_id' => $goods_id,
			'description' => $description
		));

		$photoFileName = $file->getTemporaryFile();
		$photoFileNameBig = self::SAVE_DIR . $lastPhoto_id . '.big.jpg';
		$photoFileNameSmall = self::SAVE_DIR . $lastPhoto_id . '.small.jpg';

		try {
			$src = imagecreatefromjpeg($photoFileName);
			list($width, $height) = getimagesize($photoFileName);

			$aspectRatio = $width / $height;

			if ($aspectRatio > 1) {
				$targetWidth = 800;
				$targetHeight = round(800 / $aspectRatio);
			} else {
				$targetWidth = round(800 * $aspectRatio);
				$targetHeight = 800;
			}

			$bigThumbnail = imagecreatetruecolor($targetWidth, $targetHeight);
			imagecopyresampled($bigThumbnail, $src, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
			imagejpeg($bigThumbnail, $photoFileNameBig, 75);

			$smallThumbnail = imagecreatetruecolor(round($targetWidth / 4), round($targetHeight / 4));
			imagecopyresampled($smallThumbnail, $src, 0, 0, 0, 0, round($targetWidth / 4), round($targetHeight / 4), $width, $height);
			imagejpeg($smallThumbnail, $photoFileNameSmall, 50);


		} catch (\Exception $e) {
			$photo = new Photo($this->db);
			$photo->where('id', $lastPhoto_id)->delete();
			return false;
		}
		return true;
	}

	public function deletePhoto($id) {
		try {
			unlink(self::SAVE_DIR . $id . '.big.jpg');
			unlink(self::SAVE_DIR . $id . '.small.jpg');
		} catch (\Exception $e) {
			return false;
		}

		$photo = new Photo($this->db);
		$photo->where('id', $id)->delete();
		return true;
	}

	/**
	 * Get file extension from filename
	 * @param string $filename
	 * @return string
	 */
	public static function getExtensionByName($filename) {
		$tmp = explode('.', $filename);
		return strtolower(end($tmp));
	}

}
