<?php

namespace App\Http\Helpers;

use App\Http\Controllers\Abstracts\AbstractHelper;
use App\Models\Category;
use Illuminate\Validation\ValidationException;

/**
 * Class CategoryHelper
 * @author Michal Mendel <mendel.michal096@gmail.com>
 */
class CategoryHelper extends AbstractHelper
{
    /**
     * @var string
     */
    private $errorBag;

    /**
     * CategoryHelper constructor.
     * @param string $errorBag
     */
    public function __construct(string $errorBag = 'default')
    {
        $this->errorBag = $errorBag;
    }

    /**
     * Static Function for controllers to get compacted categories to view
     * @return array
     */
    public static function getList()
    {
        $mainCategories = Category::where('parent_id', 0)->orderBy('node_index')->get();
        $allCategories = Category::pluck('name', 'id')->all();

        return compact('mainCategories', 'allCategories');
    }

    /**
     * Function to get specific item by his 'id' attribute
     * @param string $id
     * @param string $errorBag
     * @param string|null $fieldName
     * @return mixed
     * @throws ValidationException
     */
    public function getItem(string $id, string $fieldName = 'id')
    {
        $category = Category::find($id);
        if(!$category)
        {
            $this->throwCustomErrorBag($fieldName, 'Selected category does not exists', $this->errorBag);
        }

        return $category;
    }

    /**
     * Checking name uniqueness in node
     * @param string $name
     * @param string $parentId
     * @param string $errorBag
     * @return bool
     * @throws ValidationException
     */
    public function checkUniqueInNode(string $name, string $parentId)
    {
        if(Category::where(['parent_id' => $parentId, 'name' => $name])->exists())
        {
            $this->throwCustomErrorBag('name',
                'Entered name already exists in selected node', $this->errorBag);
        }

        return true;
    }

    /**
     * Getting default node_index as highest one +1
     * @param string $parentId
     * @return int
     */
    public function getDefaultNodeIndex(string $parentId)
    {
        $highestIndex = Category::where('parent_id', $parentId)->max('node_index');
        return $highestIndex === null ? 0 : ($highestIndex + 1);
    }

    /**
     * Recursive function for checking if category with checkingId is child of categoryId
     * @param string $checkingId
     * @param string $categoryId
     * @throws ValidationException
     */
    public function checkParentCorrectness(string $checkingId, string $categoryId)
    {
        $subcategories = Category::find($categoryId)->subcategories()->get();
        foreach($subcategories as $subcategory)
        {
            if($subcategory->id == $checkingId)
            {
                $this->throwCustomErrorBag('parent_id',
                    'Cannot move selected category to his child category', $this->errorBag);
            }

            $this->checkParentCorrectness($checkingId, $subcategory->id);
        }
    }

    public function getChildSubcategories(string $categoryId, array $categoriesId)
    {
        $subcategories = Category::find($categoryId)->subcategories()->get();
        foreach($subcategories as $subcategory)
        {
            /* Adding to array and execute recursive */
            $categoriesId[] = $subcategory->id;
            $categoriesId = $this->getChildSubcategories($subcategory->id, $categoriesId);
        }

        return $categoriesId;
    }

    /**
     * Public layer for throwing custom errors
     * @param string $fieldName
     * @param string $message
     * @throws ValidationException
     */
    public function throwCustomError(string $fieldName, string $message)
    {
        $this->throwCustomErrorBag($fieldName, $message, $this->errorBag);
    }
}
