<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CategoryHelper;
use App\Http\Validators\CategoryValidator;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Class CategoryController
 * @author Michal Mendel <mendel.michal096@gmail.com>
 */
class CategoryController extends Controller
{

    /**
     * CategoryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Getting listed categories
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listCategories()
    {
        return view('categoriesView', CategoryHelper::getList());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function addCategory(Request $request)
    {
        $errorBag = 'add';
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['required'],
        ];

        $data = $this->processData($errorBag, $validationRules, $request);

        $categoryHelper = new CategoryHelper($errorBag);
        $categoryHelper->checkUniqueInNode($data['name'], $data['parent_id']);

        /* Adding new category with last index by default */
        $data['node_index'] = $categoryHelper->getDefaultNodeIndex($data['parent_id']);

        Category::create($data);

        return back()->with('add-success', 'Category has been created successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function editCategory(Request $request)
    {
        $errorBag = 'edit';
        $validationRules = [
            'id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['required'],
            'node_index' => ['required']
        ];

        $data = $this->processData($errorBag, $validationRules, $request);

        /* Getting category and performing additional validations */
        $categoryHelper = new CategoryHelper($errorBag);
        $category = $categoryHelper->getItem($data['id'], 'id');
        $isChangingNode = ($category->parent_id != $data['parent_id']) ? 1 : 0;

        /* Performing special validations */
        CategoryValidator::editCategoryValidation($categoryHelper, $category, $data, $isChangingNode);

        /* Execute stored procedure for updating node_indexes and update record */
        $category->executeUpdateIndexProc($category->id, $data['parent_id'], $data['node_index'], $category->node_index, $isChangingNode, 0);
        $category->update($data);

        return back()->with('edit-success', 'Category has been modified successfully');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function deleteCategory(Request $request)
    {
        $errorBag = 'delete';
        $validationRules = [
            'id' => ['required'],
        ];

        $data = $this->processData($errorBag, $validationRules, $request);
        $categoryHelper = new CategoryHelper($errorBag);
        $category = $categoryHelper->getItem($data['id'], 'id');

        /* Performing special validations and getting category family ID list */
        CategoryValidator::deleteCategoryValidation($categoryHelper, $category, $data);
        $categoryDeleteIds = $categoryHelper->getChildSubcategories($category->id, [$category->id]);

        /* Execute stored procedure for updating node_indexes and delete records */
        $category->executeUpdateIndexProc($category->id, $category->parent_id, 0, $category->node_index, 0, 1);
        Category::whereIn('id', $categoryDeleteIds)->delete();

        return back()->with('delete-success', 'Category has been deleted successfully');
    }

    /**
     * Function for processing request data - validation & getting variables
     *
     * @param string $bagName
     * @param array $validator
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    private function processData(string $bagName, array $validator, Request $request)
    {
        if(!empty($validator))
        {
            $this->validateWithBag($bagName, $request, $validator);
        }

        /* Additional name input validation */
        $requestVariables = $request->all();
        if(isset($requestVariables['name']))
        {
            $requestVariables['name'] = (new CategoryHelper())->secureValue($requestVariables['name']);
        }

        return $requestVariables;
    }
}
