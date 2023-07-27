<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Model;

class MealPlanOptionValue extends Model
{
     /**
     * @var array The model table column to convert to dates on insert/update
     */
    public $timestamps = true;

    protected $guarded = [];

    protected $casts = [
        'value' => 'string',
        'priority' => 'integer',
        'meal_plan_option_id' => 'integer',
        'price' => 'float'
    ];

    public $relation = [
        'belongsTo' => [
            'meal_plan_option' => ['Igniter\MealPlan\Models\MealPlanOption', 'delete' => true]
        ],
    ];

    public static function getRecordEditorOptions()
    {
        $query = self::selectRaw('id, name');
        return $query->orderBy('name')->dropdown('name');
    }

    public function getDisplayTypeOptions()
    {
        return [
            'radio' => 'lang:admin::lang.menu_options.text_radio',
            'checkbox' => 'lang:admin::lang.menu_options.text_checkbox',
            'select' => 'lang:admin::lang.menu_options.text_select',
            'quantity' => 'lang:admin::lang.menu_options.text_quantity',
        ];
    }

    public function attachToMenu($menu)
    {
        $menuItemOption = $menu->menu_options()->create([
            'meal_plan_option_id' => $this->getKey(),
        ]);

        $this->option_values()->get()->each(function ($model) use ($menuItemOption) {
            $menuItemOption->menu_option_values()->create([
                'menu_option_id' => $menuItemOption->menu_option_id,
                'option_value_id' => $model->option_value_id,
                'new_price' => $model->price,
            ]);
        });
    }

}
