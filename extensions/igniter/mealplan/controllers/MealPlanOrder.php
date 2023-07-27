<?php

namespace Igniter\MealPlan\Controllers;

use Admin\Facades\AdminMenu;
use Igniter\MealPlan\Models\MealPlanOption;
use Igniter\MealPlan\Models\MealPlanAddon;
use Igniter\Flame\Exception\ApplicationException;

class MealPlanOrder extends \Admin\Classes\AdminController
{
    public $implement = [
        \Admin\Actions\ListController::class,
        \Admin\Actions\FormController::class
    ];

    public $listConfig = [
        'list' => [
            'model' => \Igniter\MealPlan\Models\MealPlan::class,
            'title' => 'MealPlan Order',
            'emptyMessage' => 'lang:igniter.local::default.reviews.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'MealPlan',
        ],
    ];

    public $formConfig = [
        'name' => 'lang:igniter.local::default.reviews.text_form_name',
        'model' => \Igniter\MealPlan\Models\MealPlan::class,
        'request' => \Igniter\MealPlan\Requests\MealPlan::class,
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniter/mealplan/mealplanorder/edit/{id}',
            'redirectClose' => 'igniter/mealplan/mealplanorder',
            'redirectNew' => 'igniter/mealplan/mealplanorder/create',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniter/mealplan/mealplanorder/edit/{id}',
            'redirectClose' => 'igniter/mealplan/mealplanorder',
            'redirectNew' => 'igniter/mealplan/mealplanorder/create',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniter/mealplan/mealplanorder',
        ],
        'delete' => [
            'redirect' => 'igniter/mealplan/mealplanorder',
        ],
        'configFile' => 'mealplan',
    ];

    protected $requiredPermissions = 'Admin.MealPlan';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('sales', 'meal_plan_orders');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
    }

}
