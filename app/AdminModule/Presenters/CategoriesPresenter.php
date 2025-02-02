<?php

namespace App\AdminModule\Presenters;

use App\AdminModule\Datagrids\CategoriesDataGrid\CategoriesDataGridControl;
use App\AdminModule\Datagrids\CategoriesDataGrid\CategoriesDataGridControlFactory;
use App\AdminModule\Forms\CategoryEditFormFactory;
use App\Model\Facades\CategoriesFacade;
use Exception;
use Nette\Application\AbortException;
use Nette\Forms\Form;

/**
 * Class CategoriesPresenter
 * @package App\AdminModule\Presenters
 * @author Jiří Andrlík
 */
final class CategoriesPresenter extends BasePresenter
{

    public function __construct(
        private readonly CategoriesFacade          $categoriesFacade,
        private readonly CategoryEditFormFactory   $categoryEditFormFactory,
        private readonly CategoriesDataGridControlFactory $categoriesDataGridControlFactory
    )
    {
        parent::__construct();
    }

    public function renderDefault(): void
    {
    }

    public function renderAdd(): void
    {
    }

	/**
	 * @throws AbortException
	 */
	public function renderEdit(int $id): void
	{
        try {
            $category = $this->categoriesFacade->getCategoryById($id);
        } catch (Exception $e) {
            $this->flashMessage('Požadovaná kategorie nebyla nalezena.', 'info');
            $this->redirect('Categories:default');
        }
        $form = $this->getComponent('categoryEditForm');
        $form->setDefaults(
            ['categoryId' => $category->id,
                'name' => $category->name,
                'showed' => $category->showed]
        );
        if (!empty($category->parent)) {
            $form->setDefaults(['parent' => $category->parent->id]);
        }

        $this->template->category = $category;
    }

    public function createComponentCategoriesDataGrid(): CategoriesDataGridControl
    {
        return $this->categoriesDataGridControlFactory->create();
    }

    public function createComponentCategoryEditForm(): Form
    {
        $onSuccess = function (string $message) {
            $this->flashMessage($message, 'success');
            $this->redirect('Categories:default');
        };

        $onFailure = function (string $message) {
            $this->flashMessage($message, 'danger');
            $this->redirect('this');
        };

        return $this->categoryEditFormFactory->create($onSuccess, $onFailure);
    }

}