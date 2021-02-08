<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Category
 * @author Michal Mendel <mendel.michal096@gmail.com>
 */
class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'parent_id',
        'node_index'
    ];

    /**
     * Defining relation
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subcategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id')->orderBy('node_index');
    }

    /**
     * Stored procedure to update node index in database
     * @param $categoryId
     * @param $parentId
     * @param $nodeIndex
     * @param $currentIndex
     * @param int $isNewNode
     * @param int $isDeletionOperation
     */
    public function executeUpdateIndexProc($categoryId, $parentId, $nodeIndex, $currentIndex, $isNewNode = 0, $isDeletionOperation = 0)
    {
        DB::select('call updateNodeIndex('.$categoryId.', '.$parentId.', '.$nodeIndex.',
         '.$currentIndex.', '.$isNewNode.', '.$isDeletionOperation.')');
    }
}
