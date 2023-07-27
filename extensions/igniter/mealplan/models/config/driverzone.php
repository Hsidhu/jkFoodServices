<?php
$config['list']['filter'] = [
    'search' => [
        'prompt' => 'Search by plan name',
        'mode' => 'all',
    ],
    'scopes' => [
        'status' => [
            'label' => 'lang:admin::lang.text_filter_status',
            'type' => 'switch',
            'conditions' => 'status = :filtered',
        ]
    ],
];

$config['list']['toolbar'] = [
    'buttons' => [
        'create' => [
            'label' => 'lang:admin::lang.button_new',
            'class' => 'btn btn-primary',
            'href' => 'igniter/mealplan/driverzone/create',
        ],
        'customer_zone' => [
            'label' => 'Customer Zone',
            'class' => 'btn btn-default',
            'href' => 'igniter/mealplan/customerzone'
        ],
    ],
];

$config['list']['columns'] = [
    'edit' => [
        'type' => 'button',
        'iconCssClass' => 'fa fa-pencil',
        'attributes' => [
            'class' => 'btn btn-edit',
            'href' => 'igniter/mealplan/driverzone/edit/{id}',
        ],
    ],
    'staff_name' => [
        'label' => 'Driver Name',
        'relation' => 'staff',
        'select' => 'staff_name'
    ],
    'area_name' => [
        'label' => 'Area Name',
        'relation' => 'area',
        'select' => 'name',
    ],
    'status' => [
        'label' => 'lang:admin::lang.label_status',
        'type' => 'switch',
        'onText' => 'lang:igniter.local::default.reviews.text_approved',
        'offText' => 'lang:igniter.local::default.reviews.text_pending_review',
    ]
];

$config['form']['toolbar'] = [
    'buttons' => [
        'back' => [
            'label' => 'lang:admin::lang.button_icon_back',
            'class' => 'btn btn-outline-secondary',
            'href' => 'igniter/mealplan/driverzone',
        ],
        'save' => [
            'label' => 'lang:admin::lang.button_save',
            'context' => ['create', 'edit'],
            'partial' => 'form/toolbar_save_button',
            'class' => 'btn btn-primary',
            'data-request' => 'onSave',
            'data-progress-indicator' => 'admin::lang.text_saving',
        ],
        'delete' => [
            'label' => 'lang:admin::lang.button_icon_delete',
            'class' => 'btn btn-danger',
            'data-request' => 'onDelete',
            'data-request-data' => "_method:'DELETE'",
            'data-request-confirm' => 'lang:admin::lang.alert_warning_confirm',
            'data-progress-indicator' => 'admin::lang.text_deleting',
            'context' => ['edit'],
        ],
    ]
];


$config['form']['fields'] = [

    'staff_id' => [
        'label' => 'Driver',
        'type' => 'relation',
        'relationFrom' => 'staff',
        'nameFrom' => 'staff_name',
        'span' => 'left',
        'placeholder' => 'lang:admin::lang.text_please_select',
    ],
    'area_id' => [
        'label' => 'Area',
        'type' => 'relation',
        'relationFrom' => 'area',
        'nameFrom' => 'name',
        'span' => 'right',
        'placeholder' => 'lang:admin::lang.text_please_select',
    ],
    'status' => [
        'label' => 'lang:admin::lang.label_status',
        'type' => 'switch',
        'default' => true,
    ],
];

return $config;
