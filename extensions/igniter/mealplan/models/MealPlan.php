<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Attach\HasMedia;
use Igniter\Flame\Database\Model;
use Igniter\Flame\Database\Traits\Purgeable;
use Igniter\Flame\Cart\Contracts\Buyable;

class MealPlan extends Model implements Buyable
{
    use HasMedia, Purgeable;

     /**
     * @var array The model table column to convert to dates on insert/update
     */
    public $timestamps = true;

    protected $guarded = [];

    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'short_description' => 'string',
        'duration' => 'string',
        'price' => 'float',
        'discount' => 'float',
        'status' => 'boolean',
    ];

    public $relation = [
        'hasMany' => [
            'mealplan_addon' => ['Igniter\MealPlan\Models\MealPlanAddon',  'foreignKey' => 'meal_plan_id', 'delete' => true],
            'meal_plan_orders' => ['Igniter\MealPlan\Models\MealPlanOrder', 'foreignKey' => 'meal_plan_id', 'delete' => false],
        ],
    ];

    protected $purgeable = ['mealplan_addon'];

    public $mediable = ['thumb'];

    /**
     * The accessors to append to the model's array form.
     * @var array
     */
    protected $appends = [
        'thumb_url',
    ];

    public static $allowedSortingColumns = ['created_at asc', 'created_at desc'];

    public function getThumbUrlAttribute()
    {
        if (!$this->hasMedia('thumb')) {
            return '';
        }

        return $this->getFirstMedia('thumb')->getPath();
    }

    public function getMealPriceFromAttribute()
    {
        if (!$this->mealplan_addon)
            return $this->price;

        return $this->mealplan_addon->mapWithKeys(function ($option) {
             $option->meal_plan_options->keyBy('meal_plan_option_id');
        })->min('price') ?: 0;
    }

    //
    // Scopes for filter
    //
    public function scopeListFrontEnd($query, $options = [])
    {
        extract(array_merge([
            'page' => 1,
            'pageLimit' => 20,
            'sort' => null,
            'name' => null
        ], $options));

        if (is_numeric($name)) {
            $query->where('name', $name);
        }

        if (!is_array($sort)) {
            $sort = [$sort];
        }

        foreach ($sort as $_sort) {
            if (in_array($_sort, self::$allowedSortingColumns)) {
                $parts = explode(' ', $_sort);
                if (count($parts) < 2) {
                    array_push($parts, 'desc');
                }
                [$sortField, $sortDirection] = $parts;
                $query->orderBy($sortField, $sortDirection);
            }
        }

        $this->fireEvent('model.extendListFrontEndQuery', [$query]);

        return $query->paginate($pageLimit, $page);
    }

    public function scopeIsEnabled($query)
    {
        return $query->where('status', 1);
    }

    //
    // Events
    //

    protected function afterSave()
    {
        $this->restorePurgedValues();

        if (array_key_exists('mealplan_addon', $this->attributes))
            $this->addMealAddon((array)$this->attributes['mealplan_addon']);

    }

    /**
     * Create new or update existing menu options
     *
     * @param array $menuOptions if empty all existing records will be deleted
     *
     * @return bool
     */
    public function addMealAddon(array $mealAddons = [])
    {
        $mealId = $this->getKey();
        if (!is_numeric($mealId))
            return false;

        $idsToKeep = [];
        foreach ($mealAddons as $option) {
            $option['meal_plan_id'] = $mealId;
            $menuOption = $this->mealplan_addon()->firstOrNew([
                'meal_plan_option_id' => array_get($option, 'meal_plan_option_id'),
            ])->fill(array_except($option, ['meal_plan_option_id']));

            $menuOption->saveOrFail();
            $idsToKeep[] = $menuOption->getKey();
        }

        $this->mealplan_addon()->whereNotIn('meal_plan_option_id', $idsToKeep)->delete();

        return count($idsToKeep);
    }

    public function hasOptions()
    {
        return count($this->mealplan_addon);
    }


    public static function findBy($mealplanId)
    {
        $query = self::query();
        return $query->isEnabled()->whereKey($mealplanId)->first();
    }

    /**
     * Get the identifier of the Buyable item.
     *
     * @return int|string
     */
    public function getBuyableIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the description or title of the Buyable item.
     *
     * @return string
     */
    public function getBuyableName()
    {
        return $this->name;
    }

    /**
     * Get the price of the Buyable item.
     *
     * @return float
     */
    public function getBuyablePrice()
    {
        return $this->price;
    }

}
