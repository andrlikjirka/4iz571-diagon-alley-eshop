<?php

declare(strict_types=1);

namespace App\AdminModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\CategoriesFacade;
use App\Model\Facades\ProductsFacade;
use App\Model\Orm\ProductPhotos\ProductPhoto;
use App\Model\Orm\Products\Product;
use App\Model\Uploader\Uploader;
use Closure;
use Exception;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nette\Utils\Arrays;
use Zet\FileUpload\FileUploadControl;


/**
 * Class ProductsFormFactory
 * @package App\AdminModule\Forms
 * @author Martin Kovalski
 */
class ProductsFormFactory
{
	private Closure $onSuccess;
	private Closure $onFailure;

	public function __construct(
		private readonly FormFactory $formFactory,
		private readonly ProductsFacade $productsFacade,
		private readonly CategoriesFacade $categoriesFacade,
		private readonly Uploader $uploader
	) {}

	public function create(callable $onSuccess, callable $onFailure): Form
	{
		$form = $this->formFactory->create();

		$form->addHidden('productId')
			->setNullable();

		$form->addText('name', 'Název produktu', maxLength: 255)
			->setRequired();

		$form->addTextArea('description', 'Popis produktu');

		$form->addInteger('stock', 'Počet kusů skladem')
			->setDefaultValue(0)
			->setRequired();

		$form->addSelect('category', 'Kategorie', $this->categoriesFacade->findAllCategoriesPairs())
			->setPrompt('-- Nezařazeno --');

		$form->addCheckbox('showed', 'Zobrazovat na stránce');

		$form->addInteger('galleonPrice', 'Cena (galeony)')
			->setDefaultValue(0)
			->setRequired();

		$form->addInteger('sicklePrice', 'Cena (srpce)')
			->setDefaultValue(0)
			->setRequired();

		$form->addInteger('knutPrice', 'Cena (svrčky)')
			->setDefaultValue(0)
			->setRequired();

		$form->addFileUpload('Fotografie');

		$form->addSubmit('save', 'Uložit produkt');

		$form->onSuccess[] = $this->formSucceeded(...);

		$this->onSuccess = $onSuccess;
		$this->onFailure = $onFailure;

		return $form;
	}

	private function formSucceeded(Form $form, ArrayHash $values): void
	{
		$photos = $values->Fotografie;
		unset($values->Fotografie);

		if($values->productId) {
			$product = $this->productsFacade->getProduct((int)$values->productId);
		} else {
			$product = new Product();
		}
		unset($values['productId']);

		Arrays::toObject($values, $product);

		try {
			$this->productsFacade->saveProduct($product);
		} catch (Exception $e) {
			($this->onFailure)($e->getMessage());
			return;
		}

		//process images
		if($photos) {
			try {
				foreach ($photos as $photo) {
					$productPhoto = new ProductPhoto();
					$productPhoto->name = $photo;
					$productPhoto->product = $product;
					$this->productsFacade->saveProductPhoto($productPhoto);
				}
			} catch(Exception $e) {
				($this->onFailure)($e->getMessage());
				return;
			}
		}

		($this->onSuccess)('Produkt byl úspěšně uložen');
	}
}