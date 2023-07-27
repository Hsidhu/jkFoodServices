<?php

namespace Igniter\MealPlan\Controllers;

use Admin\Facades\AdminMenu;

class CustomerZone extends \Admin\Classes\AdminController
{
    public $implement = [
        \Admin\Actions\ListController::class,
        \Admin\Actions\FormController::class
    ];

    public $listConfig = [
        'list' => [
            'model' => \Igniter\MealPlan\Models\CustomerZone::class,
            'title' => 'Customer Zone',
            'emptyMessage' => 'lang:igniter.local::default.reviews.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'customerzone'
        ],
    ];

    public $formConfig = [
        'name' => 'Customer Zone',
        'model' => \Igniter\MealPlan\Models\CustomerZone::class,
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniter/mealplan/customerzone',
            'redirectClose' => 'igniter/mealplan/customerzone',
            'redirectNew' => 'igniter/mealplan/customerzone/create',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniter/mealplan/customerzone/edit/{id}',
            'redirectClose' => 'igniter/mealplan/customerzone',
            'redirectNew' => 'igniter/mealplan/customerzone/create',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniter/mealplan/customerzone',
        ],
        'delete' => [
            'redirect' => 'igniter/mealplan/customerzone',
        ],
        'configFile' => 'customerzone',
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
