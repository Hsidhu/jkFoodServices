<?php

namespace Igniter\MealPlan\Models;

use Carbon\Carbon;
use Igniter\Flame\Database\Model;

class MealPlanOrderTransaction extends Model
{
     /**
     * @var array The model table column to convert to dates on insert/update
     */
    public $timestamps = true;

    protected $guarded = [];

    public $relation = [
        'belongsTo' => [
            'meal_plan_order' =>['Igniter\MealPlan\Models\MealPlanOrder'],
            'customer' => ['Admin\Models\Customers_model'],
            'staff' => ['Admin\Models\Staffs_model']
        ]
    ];

    public function getStaffNameAttribute($value)
    {
        return ($this->staff && $this->staff->exists) ? $this->staff->staff_name : $value;
    }

    public function getDateAddedSinceAttribute($value)
    {
        return $this->created_at ? time_elapsed($this->created_at) : null;
    }


    /**
     * @param \Igniter\Flame\Database\Model|mixed $status
     * @param \Igniter\Flame\Database\Model|mixed $object
     * @param array $options
     * @return static|bool
     */
    public static function createHistory($status, $object, $options = [])
    {
        $status = '';
        $previousStatus = $object->getOriginal('status');

        // get customer id from meal plan id
        $model = new static;
        $model->status = $status;
        $model->meal_plan_order_id = $object->getKey();
        $model->customer_id = 1;
        $model->staff_id = array_get($options, 'staff_id');

        if ($model->fireSystemEvent('mealplan.orderStatusHistory.beforeAddStatus', [$object, $status, $previousStatus], true) === false)
            return false;

        $model->save();

        // Update using query to prevent model events from firing
        $object->newQuery()->where($object->getKeyName(), $object->getKey())->update([
            'status' => $status,
        ]);

        return $model;
    }

    public function scopeWhereStatusIsLatest($query, $status)
    {
        return $query->where('status', $status)->orderBy('created_at', 'desc');
    }

}
