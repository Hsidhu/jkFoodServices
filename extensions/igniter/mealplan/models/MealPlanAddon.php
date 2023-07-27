<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Model;
use Igniter\Flame\Database\Traits\Purgeable;

class MealPlanAddon extends Model
{
    use Purgeable;
     /**
     * @var array The model table column to convert to dates on insert/update
     */
    public $timestamps = true;

    protected $guarded = [];

    protected $purgeable = ['menu_plan_option_values'];
    
    public $relation = [
        'belongsTo' => [
            'meal_plan' => ['Igniter\MealPlan\Models\MealPlan'],
            'meal_plan_option' => ['Igniter\MealPlan\Models\MealPlanOption']
        ]
    ];

    public function getDisplayTypeOptions()
    {
        return [
            'radio' => 'lang:admin::lang.menu_options.text_radio',
            'checkbox' => 'lang:admin::lang.menu_options.text_checkbox',
            'select' => 'lang:admin::lang.menu_options.text_select',
            'quantity' => 'lang:admin::lang.menu_options.text_quantity',
        ];
    }

    public function getNameAttribute()
    {
        return optional($this->meal_plan_option)->name;
    }

    public function getDisplayTypeAttribute()
    {
        return optional($this->meal_plan_option)->display_type;
    }

    
    //
    // Events
    //

    protected function afterSave()
    {
        $this->restorePurgedValues();

        if (array_key_exists('meal_plan_option_values', $this->attributes))
            $this->addMenuOptionValues($this->attributes['meal_plan_option_values']);
    }

    //
    // Helpers
    //

    public function isSelectDisplayType()
    {
        return $this->display_type === 'select';
    }

    /**
     * Create new or update existing menu option values
     *
     * @param int $menuOptionId
     * @param int $optionId
     * @param array $optionValues if empty all existing records will be deleted
     *
     * @return bool
     */
    public function addMenuOptionValues(array $optionValues = [])
    {
        $menuOptionId = $this->getKey();
        if (!is_numeric($menuOptionId))
            return false;

        $idsToKeep = [];
        foreach ($optionValues as $value) {
            $menuOptionValue = $this->meal_plan_option_values()->firstOrNew([
                'id' => array_get($value, 'id'),
                'meal_plan_option_id' => $menuOptionId,
            ])->fill(array_except($value, ['id', 'meal_plan_option_id']));

            $menuOptionValue->saveOrFail();
            $idsToKeep[] = $menuOptionValue->getKey();
        }

        $this->meal_plan_option_values()
            ->where('meal_plan_option_id', $menuOptionId)
            ->whereNotIn('id', $idsToKeep)
            ->delete();

        return count($idsToKeep);
    }
}
