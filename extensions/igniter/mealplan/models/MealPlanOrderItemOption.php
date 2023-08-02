<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Attach\HasMedia;
//use IgniterLabs\ImportExport\Models\ExportModel;
use Igniter\Flame\Database\Model;
use Igniter\MealPlan\Models\MealPlanOption;

class MealPlanOrderItemOption extends Model
{
    use HasMedia;

     /**
     * @var array The model table column to convert to dates on insert/update
     */
    public $timestamps = true;

    protected $guarded = [];

    public $relation = [
        'belongsTo' => [
            'meal_plan_order' =>['Igniter\MealPlan\Models\MealPlanOrder'],
            'meal_plan_addon' =>['Igniter\MealPlan\Models\MealPlanAddon']
        ]
    ];

}
