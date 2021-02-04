<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'parent_id'
    ];

    public function subcategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }
}
