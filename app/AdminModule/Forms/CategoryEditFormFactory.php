<?php

namespace App\AdminModule\Forms;

use App\Forms\FormFactory;
use App\Model\Facades\CategoriesFacade;
use App\Model\Orm\Categories\Category;
use Closure;
use Exception;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Form;
use Nette\Utils\ArrayHash;
use Nette\Utils\Strings;

/**
 * Class CategoryEditFormFactory
 * @package App\AdminModule\Forms
 * @author Jiří Andrlík
 */
class CategoryEditFormFactory
{
    private Closure $onSuccess;
    private Closure $onFailure;

    public function __construct(
        private readonly FormFactory      $formFactory,
        private readonly CategoriesFacade $categoriesFacade
    )
    {
    }

    public function create(callable $onSuccess, callable $onFailure): Form
    {
        $form = $this->formFactory->create();

        $form->addHidden('categoryId')
            ->setNullable();

        $form->addText('name', 'Název kategorie', maxLength: 255)
            ->setRequired()
            ->setHtmlAttribute('placeholder', 'Zadejte název kategorie')
            ->setHtmlAttribute('autofocus')
			->addRule(function (TextInput $textInput) use ($form) {
				$slug = Strings::webalize($textInput->value);
				try {
					$category = $this->categoriesFacade->getCategoryBySlug($slug);
					return $category->id == $form['categoryId']->getValue(); //check if it's not the same product
				} catch (Exception $e) {
					// if it can't find category with this slug, it's ok
					return true;
				}
				return false;
			}, 'Kategorie se stejným názvem již existuje');

        $categories = $this->categoriesFacade->findAllCategories();
        $categoriesArray = array();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $categoriesArray[$category->id] = $category->name;
            }
        }
        $form->addSelect('parent', 'Nadřazená kategorie', $categoriesArray)
            ->setPrompt('Zvolte nadřazenou kategorii');

        $form->addSelect('showed','Zobrazovat kategorii', [
            0 => "Ne",
            1 => "Ano"
        ])
            ->setDefaultValue(0);

        $form->addSubmit('save', 'Uložit kategorii');

        $form->onSuccess[] = $this->formSucceeded(...);

        $this->onSuccess = $onSuccess;
        $this->onFailure = $onFailure;

        return $form;
    }

    public function formSucceeded(Form $form, ArrayHash $values): void
    {
        if ($values->categoryId) {
            try {
                $category = $this->categoriesFacade->getCategoryById(intval($values->categoryId));
            } catch (\Exception $e) {
                ($this->onFailure)('Kategorie nenalezena!');
                return;
            }
        } else {
            $category = new Category();
        }

        $category->name = $values->name;
		$category->slug = Strings::webalize($values->name);

        if (!empty($values->parent)) {
            try {
                $parent = $this->categoriesFacade->getCategoryById($values->parent);
            } catch (\Exception $e) {
                ($this->onFailure)('Nadřízená kategorie nenalezena!');
                return;
            }
            $category->parent = $parent;
        } else {
            $category->parent = null;
        }

        $category->showed = $values->showed;

        try {
            $this->categoriesFacade->saveCategory($category);
        } catch (\Exception $e) {
            ($this->onFailure)($e->getMessage());
            return;
        }

        ($this->onSuccess)('Kategorie byla úspěšně uložena.');
    }
}