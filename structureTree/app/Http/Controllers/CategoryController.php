<?php

namespace App\Http\Controllers;

use App\Http\Helpers\CategoryHelper;
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
        return view('categoriesView', (new CategoryHelper)->getList());
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

        $categoryHelper = new CategoryHelper();
        $categoryHelper->checkUniqueInNode($data['name'], $data['parent_id'], $errorBag);

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
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['required'],
        ];

        $data = $this->processData($errorBag, $validationRules, $request);

        /* Getting category and performing additional validations */
        $categoryHelper = new CategoryHelper();
        $category = $categoryHelper->getItem($data['id'], $errorBag);
        $categoryHelper->checkUniqueInNode($data['name'], $data['parent_id'], $errorBag);

        $category->update($data);

        return back()->with('edit-success', 'Category has been modified successfully');
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
    private function processData($bagName, array $validator, Request $request)
    {
        if(!empty($validator))
        {
            $this->validateWithBag($bagName, $request, $validator);
        }

        /* Additional name input validation */
        $requestVariables = $request->all();
        if($requestVariables['name'])
        {
            $requestVariables['name'] = (new CategoryHelper())->secureValue($requestVariables['name']);
        }

        return $requestVariables;
    }
}
