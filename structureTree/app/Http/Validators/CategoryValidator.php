<?php

namespace App\Http\Validators;

use App\Http\Helpers\CategoryHelper;
use App\Models\Category;

/**
 * Class CategoryValidator
 * @author Michal Mendel <mendel.michal096@gmail.com>
 */
class CategoryValidator
{
    /**
     * Edit Category Validator
     * @param CategoryHelper $categoryHelper
     * @param Category $category
     * @param array $data
     * @param int $isChangingNode
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function editCategoryValidation(CategoryHelper $categoryHelper, Category $category, array $data,
                                            int $isChangingNode)
    {
        /* If user tries to allocate category as his parent_id */
        if($category->id == $data['parent_id'])
        {
            $categoryHelper->throwCustomError('parent_id',
                'Cannot assign category as his own parent');
        }

        /* If changing node need to check parent existance */
        if($isChangingNode && $data['parent_id'] != 0)
        {
            $categoryHelper->getItem($data['parent_id'], 'parent_id');
        }

        /* If user tries to cheat and has manually removed the lock or the script has an error need to check integrity */
        if($isChangingNode) {
            $categoryHelper->checkParentCorrectness($data['parent_id'], $category->id);
        }

        /* If changing name or changing node need to check uniqueness of name attribute */
        if($isChangingNode || $category->name != $data['name'])
        {
            $categoryHelper->checkUniqueInNode($data['name'], $data['parent_id']);
        }

        return;
    }

    /**
     * Delete Category Validator
     * @param CategoryHelper $categoryHelper
     * @param Category $category
     * @param array $data
     * @throws \Illuminate\Validation\ValidationException
     */
    public static function deleteCategoryValidation(CategoryHelper $categoryHelper, Category $category, array $data)
    {
        /* If category has subcategories it need to be send approval info */
        if($category->subcategories()->exists() && $data['confirmDelete'] != 'on')
        {
            $categoryHelper->throwCustomError('id', 'Cannot perform operation of deleting multiple categories without approval');
        }

        return;
    }
}
