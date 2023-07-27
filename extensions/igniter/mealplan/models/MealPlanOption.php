<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Model;
use Igniter\Flame\Database\Traits\Purgeable;

class MealPlanOption extends Model
{
    use Purgeable;

    /**
    * @var array The model table column to convert to dates on insert/update
    */
    public $timestamps = true;

    protected $guarded = [];

    protected $purgeable = ['values'];

    public $relation = [
        'hasMany' => [
            'meal_plan_option_values' => ['Igniter\MealPlan\Models\MealPlanOptionValue', 'foreignKey' => 'meal_plan_option_id', 'delete' => true],
            'meal_addons' => ['Igniter\MealPlan\Models\MealPlanAddon', 'foreignKey' => 'meal_plan_option_id', 'delete' => true]
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


    /**
     * Create a new or update existing option values
     *
     * @param array $optionValues
     *
     * @return bool
     */
    public function addOptionValues($optionValues = [])
    {
        $optionId = $this->getKey();
        $idsToKeep = [];
        
        foreach ($optionValues as $value) {
            $optionValue = $this->meal_plan_option_values()->firstOrNew([
                'id' => array_get($value, 'id'),
                'meal_plan_option_id' => $optionId,
                'value' => array_get($value, 'value')
            ])->fill(array_except($value, ['id', 'meal_plan_option_id']));

            $optionValue->saveOrFail();
            $idsToKeep[] = $optionValue->getKey();
        }
        return count($idsToKeep);
    }


    //
    // Events
    //

    protected function afterSave()
    {
        $this->restorePurgedValues();

        if (array_key_exists('values', $this->attributes))
            $this->addOptionValues($this->attributes['values']);
    }


    //
    // Helpers
    //

    /**
     * Return all option values by meal_plan_option_id
     *
     * @param int $meal_plan_option_id
     *
     * @return array
     */
    public static function getOptionValues($meal_plan_option_id = null)
    {
        dd($meal_plan_option_id);
        $query = self::from('meal_plan_option_values');

        if ($meal_plan_option_id !== false) {
            $query->where('meal_plan_option_id', $meal_plan_option_id);
        }

        return $query->get();
    }

}
