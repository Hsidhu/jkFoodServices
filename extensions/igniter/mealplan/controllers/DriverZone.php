<?php

namespace Igniter\MealPlan\Controllers;

use Admin\Facades\AdminMenu;
use Igniter\MealPlan\Models\MealPlanOption;
use Igniter\MealPlan\Models\MealPlanAddon;
use Igniter\Flame\Exception\ApplicationException;

class DriverZone extends \Admin\Classes\AdminController
{
    public $implement = [
        \Admin\Actions\ListController::class,
        \Admin\Actions\FormController::class
    ];

    public $listConfig = [
        'list' => [
            'model' => \Igniter\MealPlan\Models\DriverZone::class,
            'title' => 'Driver Zone',
            'emptyMessage' => 'lang:igniter.local::default.reviews.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'driverzone'
        ],
    ];

    public $formConfig = [
        'name' => 'Driver Zone',
        'model' => \Igniter\MealPlan\Models\DriverZone::class,
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniter/mealplan/driverzone',
            'redirectClose' => 'igniter/mealplan/driverzone',
            'redirectNew' => 'igniter/mealplan/driverzone/create',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniter/mealplan/driverzone/edit/{id}',
            'redirectClose' => 'igniter/mealplan/driverzone',
            'redirectNew' => 'igniter/mealplan/driverzone/create',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniter/mealplan/driverzone',
        ],
        'delete' => [
            'redirect' => 'igniter/mealplan/driverzone',
        ],
        'configFile' => 'driverzone',
    ];

    protected $requiredPermissions = 'Admin.MealPlan';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('system', 'meal_plan_driver');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
    }

}
