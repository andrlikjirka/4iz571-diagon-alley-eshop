<?php

declare(strict_types=1);

namespace App\Model\Uploader;

use App\Model\Facades\ProductsFacade;
use Nette\Http\FileUpload;
use Nette\Utils\Strings;
use Zet\FileUpload\Model\IUploadModel;


/**
 * Class Uploader
 * @package App\Model\Uploader
 * @author Martin Kovalski
 */
class Uploader implements IUploadModel
{
	public function __construct(
		private readonly ProductsFacade $productsFacade
	) {}

	public function save(FileUpload $file, array $params = []): string
	{
		// get file name without extension
		$fileExtension = Strings::lower($file->getImageFileExtension());
		$originalFileName = Strings::webalize(Strings::substring($file->getSanitizedName(), 0, -1 * (Strings::length($fileExtension) + 1)));
		$fileName = $originalFileName;
		$counter = 0;
		while(true) {
			$productPhoto = $this->productsFacade->getProductPhotoByName($fileName . '.' . $fileExtension);
			if(!$productPhoto) {
				break;
			}
			$fileName = $originalFileName . '_' . ++$counter;
		}

		// save file to storage
		$file->move(__DIR__ . '/../../../www/uploads/products/' . $fileName . '.' . $fileExtension);

		return $fileName . '.' . $fileExtension;
	}

	public function rename($upload, $newName): string
	{
		return $upload;
	}

	public function remove($uploaded)
	{
		// TODO: Implement remove() method.
	}
}