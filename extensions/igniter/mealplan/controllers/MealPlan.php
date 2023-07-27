<?php

namespace Igniter\MealPlan\Controllers;

use Admin\Facades\AdminMenu;
use Igniter\MealPlan\Models\MealPlanOption;
use Igniter\MealPlan\Models\MealPlanAddon;
use Igniter\Flame\Exception\ApplicationException;

class MealPlan extends \Admin\Classes\AdminController
{
    public $implement = [
        \Admin\Actions\ListController::class,
        \Admin\Actions\FormController::class
    ];

    public $listConfig = [
        'list' => [
            'model' => \Igniter\MealPlan\Models\MealPlan::class,
            'title' => 'MealPlan',
            'emptyMessage' => 'lang:igniter.local::default.reviews.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'mealplan',
        ],
    ];

    public $formConfig = [
        'name' => 'lang:igniter.local::default.reviews.text_form_name',
        'model' => \Igniter\MealPlan\Models\MealPlan::class,
        'request' => \Igniter\MealPlan\Requests\MealPlan::class,
        'create' => [
            'title' => 'lang:admin::lang.form.create_title',
            'redirect' => 'igniter/mealplan/mealplan/edit/{id}',
            'redirectClose' => 'igniter/mealplan/mealplan',
            'redirectNew' => 'igniter/mealplan/mealplan/create',
        ],
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniter/mealplan/mealplan/edit/{id}',
            'redirectClose' => 'igniter/mealplan/mealplan',
            'redirectNew' => 'igniter/mealplan/mealplan/create',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniter/mealplan/mealplan',
        ],
        'delete' => [
            'redirect' => 'igniter/mealplan/mealplan',
        ],
        'configFile' => 'mealplan',
    ];

    protected $requiredPermissions = 'Admin.MealPlan';

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('restaurant', 'meal_plan');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
    }

    public function edit_onChooseMenuOption($context, $recordId)
    {
        $mealOptionId = post('MealPlan._options');
        if (!$menuOption = MealPlanOption::find($mealOptionId))
            throw new ApplicationException(lang('admin::lang.menus.alert_menu_option_not_attached'));

        $model = $this->asExtension('FormController')->formFindModelObject($recordId);

        MealPlanAddon::create([
            'meal_plan_id' => $model->id,
            'meal_plan_option_id' => $menuOption->id,
        ]);
        //$menuOption->attachToMenu($model);

        $model->reload();
        $this->asExtension('FormController')->initForm($model, $context);

        flash()->success(sprintf(lang('admin::lang.alert_success'), 'Menu item option attached'))->now();

        $formField = $this->widgets['form']->getField('mealplan_addon');

        return [
            '#notification' => $this->makePartial('flash'),
            '#'.$formField->getId('group') => $this->widgets['form']->renderField($formField, [
                'useContainer' => false,
            ]),
        ];
    }

}
