<?php

namespace Igniter\MealPlan\Models;

use Illuminate\Support\Facades\Request;
use Igniter\Flame\Database\Model;
use Igniter\Flame\Auth\Models\User;
use Admin\Traits\HasInvoice;
use System\Traits\SendsMailTemplate;
use Igniter\MealPlan\Traits\LogsStatusHistory;
use Igniter\MealPlan\Traits\ManagesOrderItems;

class MealPlanOrder extends Model
{
    use HasInvoice, SendsMailTemplate;
    use ManagesOrderItems, LogsStatusHistory;

    const DELIVERY = 'delivery';

    const COLLECTION = 'collection';

    public $status = [
        'received',
        'paid',
        'payment_required',
        'scheduled'
    ];
     /**
     * @var array The model table column to convert to dates on insert/update
     */
    public $timestamps = true;

    protected $guarded = [];

    public $relation = [
        'belongsTo' => [
            'meal_plan' =>['Igniter\MealPlan\Models\MealPlan'],
            'customer'  => ['Admin\Models\Customers_model']
            // 'payment_method' => ['Admin\Models\Payments_model', 'foreignKey' => 'payment', 'otherKey' => 'code'],
        ],
        'hasMany'=> [
            'meal_plan_order_options' => ['Igniter\MealPlan\Models\MealPlanOrderOption', 'foreignKey' => 'meal_plan_order_id'],
            'meal_plan_transactions' => ['Igniter\MealPlan\Models\MealPlanOrderTransaction', 'foreignKey' => 'meal_plan_order_id']
        ]
    ];

    public static $allowedSortingColumns = [
        'created_at asc', 'created_at desc',
    ];

    //
    // Events
    //

    protected function beforeCreate()
    {
        $this->generateHash();
        $this->ip_address = Request::getClientIp();
        $this->user_agent = Request::userAgent();
    }

    /**
     * Generate a unique hash for this order.
     * @return string
     */
    protected function generateHash()
    {
        $this->hash = $this->createHash();
        while ($this->newQuery()->where('hash', $this->hash)->count() > 0) {
            $this->hash = $this->createHash();
        }
    }

    //
    // Scopes
    //
    public function scopeListFrontEnd($query, $options = [])
    {
        extract(array_merge([
            'customer' => null,
            'dateTimeFilter' => [],
            'location' => null,
            'sort' => 'address_id desc',
            'search' => '',
            'status' => null,
            'page' => 1,
            'pageLimit' => 20,
        ], $options));

        $searchableFields = ['order_id', 'first_name', 'last_name', 'email', 'telephone'];

        if (is_null($status)) {
            $query->where('status_id', '>=', 1);
        } else {
            if (!is_array($status))
                $status = [$status];
            $query->whereIn('status_id', $status);
        }

        if ($customer instanceof User) {
            $query->where('customer_id', $customer->getKey());
        } elseif (strlen($customer)) {
            $query->where('customer_id', $customer);
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

        $search = trim($search);
        if (strlen($search)) {
            $query->search($search, $searchableFields);
        }

        $startDateTime = array_get($dateTimeFilter, 'orderDateTime.startAt', false);
        $endDateTime = array_get($dateTimeFilter, 'orderDateTime.endAt', false);
        if ($startDateTime && $endDateTime)
            $query = $this->scopeWhereBetweenOrderDateTime($query, Carbon::parse($startDateTime)->format('Y-m-d H:i:s'), Carbon::parse($endDateTime)->format('Y-m-d H:i:s'));

        $this->fireEvent('model.extendListFrontEndQuery', [$query]);

        return $query->paginate($pageLimit, $page);
    }

    /**
     * Get customer address
     */
    public function listCustomerAddresses()
    {
        if (!$this->customer)
            return [];

        return $this->customer->addresses()->get();
    }


    public function getOrderDatetimeAttribute($value)
    {
        if (!isset($this->attributes['order_date'])
            && !isset($this->attributes['order_time'])
        ) return null;

        return make_carbon($this->attributes['order_date'])
            ->setTimeFromTimeString($this->attributes['order_time']);
    }

    public function isCompleted()
    {
        if (!$this->isPaymentProcessed())
            return false;

        return $this->hasStatus(setting('completed_order_status'));
    }

    public function isPaymentProcessed()
    {
        return $this->processed && !empty($this->status_id);
    }

    public function isDeliveryType()
    {
        return $this->order_type == static::DELIVERY;
    }

    public function isCollectionType()
    {
        return $this->order_type == static::COLLECTION;
    }
}
