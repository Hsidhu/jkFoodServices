<?php

namespace Igniter\MealPlan\Controllers;

use Admin\ActivityTypes\StatusUpdated;
use Admin\Facades\AdminMenu;
use Igniter\MealPlan\Models\MealPlanOrder;
use Admin\Models\Statuses_model;
use Igniter\Flame\Exception\ApplicationException;

class MealPlanOrders extends \Admin\Classes\AdminController
{
    public $implement = [
        'Admin\Actions\ListController',
        'Admin\Actions\FormController',
        'Admin\Actions\LocationAwareController',
        'Admin\Actions\AssigneeController',
    ];

    public $listConfig = [
        'list' => [
            'model' => 'Igniter\MealPlan\Models\MealPlanOrder',
            'title' => 'lang:admin::lang.orders.text_title',
            'emptyMessage' => 'lang:admin::lang.orders.text_empty',
            'defaultSort' => ['id', 'DESC'],
            'configFile' => 'mealplanorder',
        ],
    ];

    public $formConfig = [
        'name' => 'lang:admin::lang.orders.text_form_name',
        'model' => 'Igniter\MealPlan\Models\MealPlanOrder',
        'request' => 'Admin\Requests\Order',
        'edit' => [
            'title' => 'lang:admin::lang.form.edit_title',
            'redirect' => 'igniter/mealplan/mealplanorders/edit/{id}',
            'redirectClose' => 'orders',
        ],
        'preview' => [
            'title' => 'lang:admin::lang.form.preview_title',
            'redirect' => 'igniter/mealplan/mealplanorders',
        ],
        'delete' => [
            'redirect' => 'igniter/mealplan/mealplanorders',
        ],
        'configFile' => 'mealplanorder',
    ];

    protected $requiredPermissions = [
        'Admin.Orders',
        'Admin.AssignOrders',
        'Admin.DeleteOrders',
    ];

    public function __construct()
    {
        parent::__construct();

        AdminMenu::setContext('meal_plan_orders', 'sales');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();

        $this->vars['statusesOptions'] = \Admin\Models\Statuses_model::getDropdownOptionsForOrder();
    }

    public function index_onDelete()
    {
        if (!$this->getUser()->hasPermission('Admin.DeleteOrders'))
            throw new ApplicationException(lang('admin::lang.alert_user_restricted'));

        return $this->asExtension('Admin\Actions\ListController')->index_onDelete();
    }

    public function index_onUpdateStatus()
    {
        $model = MealPlanOrder::find((int)post('recordId'));
        $status = Statuses_model::find((int)post('statusId'));
        if (!$model || !$status)
            return;

        if ($record = $model->addStatusHistory($status))
            StatusUpdated::log($record, $this->getUser());

        flash()->success(sprintf(lang('admin::lang.alert_success'), lang('admin::lang.statuses.text_form_name').' updated'))->now();

        return $this->redirectBack();
    }

    public function edit_onDelete($context, $recordId)
    {
        if (!$this->getUser()->hasPermission('Admin.DeleteOrders'))
            throw new ApplicationException(lang('admin::lang.alert_user_restricted'));

        return $this->asExtension('Admin\Actions\FormController')->edit_onDelete($context, $recordId);
    }

    public function invoice($context, $recordId = null)
    {
        $model = $this->formFindModelObject($recordId);

        if (!$model->hasInvoice())
            throw new ApplicationException(lang('admin::lang.orders.alert_invoice_not_generated'));

        $this->vars['model'] = $model;

        $this->suppressLayout = true;
    }

    public function formExtendQuery($query)
    {
        $query->with([
            'status_history' => function ($q) {
                $q->orderBy('created_at', 'desc');
            },
        ]);
    }
}
