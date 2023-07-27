<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Model;

/**
 * Location areas Model Class
 */
class CustomerZone extends Model
{

    public $timestamps = true;

    protected $guarded = [];

    public $relation = [
        'belongsTo' => [
            'customer' => ['Admin\Models\Customers_model'],
            'area' => ['Admin\Models\Location_areas_model'],
        ],
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'area_id' => 'integer'
    ];

    public static $allowedSortingColumns = ['created_at asc', 'created_at desc'];

    public static function getDriverOptions()
    {
        return static::isEnabled()->staff()->selectRaw('customer_id, concat(first_name, " ", last_name) as name')->dropdown('staff_name');
    }

    //
    // Scopes
    //

    //
    // Scopes for filter
    //
    public function scopeListFrontEnd($query, $options = [])
    {
        extract(array_merge([
            'page' => 1,
            'pageLimit' => 20,
            'sort' => null,
            'staff' => null,
            'area' => null,
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

}
