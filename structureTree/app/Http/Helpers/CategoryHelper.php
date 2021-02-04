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
     * Function for controllers to get compacted categories to view
     * @return array
     */
    public function getList()
    {
        $mainCategories = Category::where('parent_id', 0)->get();
        $allCategories = Category::pluck('name', 'id')->all();

        return compact('mainCategories', 'allCategories');
    }

    /**
     * Function to get specific item by his 'id' attribute
     * @param string $id
     * @param string $errorBag
     * @return mixed
     * @throws ValidationException
     */
    public function getItem(string $id, string $errorBag)
    {
        $category = Category::find($id);
        if(!$category)
        {
            $this->throwCustomErrorBag('id', 'Selected category does not exists', $errorBag);
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
    public function checkUniqueInNode(string $name, string $parentId, string $errorBag)
    {
        if(Category::where(['parent_id' => $parentId, 'name' => $name])->exists())
        {
            $this->throwCustomErrorBag('name',
                'Entered name already exists in selected node', $errorBag);
        }

        return true;
    }
}
