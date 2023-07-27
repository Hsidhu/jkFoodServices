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
        ],
        'created_at' => [
            'label' => 'lang:admin::lang.text_filter_date',
            'type' => 'daterange',
            'conditions' => 'created_at >= CAST(:filtered_start AS DATE) AND created_at <= CAST(:filtered_end AS DATE)',
        ],
    ],
];

$config['list']['toolbar'] = [
    'buttons' => [
        'create' => [
            'label' => 'lang:admin::lang.button_new',
            'class' => 'btn btn-primary',
            'href' => 'igniter/mealplan/mealplan/create',
        ],
    ],
];

$config['list']['bulkActions'] = [
    'status' => [
        'label' => 'lang:admin::lang.label_status',
        'type' => 'dropdown',
        'class' => 'btn btn-light',
        'statusColumn' => 'status',
        'menuItems' => [
            'enable' => [
                'label' => 'lang:igniter.local::default.reviews.text_approved',
                'type' => 'button',
                'class' => 'dropdown-item',
            ],
            'disable' => [
                'label' => 'lang:igniter.local::default.reviews.text_pending_review',
                'type' => 'button',
                'class' => 'dropdown-item text-danger',
            ],
        ],
    ],
    'delete' => [
        'label' => 'lang:admin::lang.button_delete',
        'class' => 'btn btn-light text-danger',
        'data-request-confirm' => 'lang:admin::lang.alert_warning_confirm',
    ],
];

$config['list']['columns'] = [
    'edit' => [
        'type' => 'button',
        'iconCssClass' => 'fa fa-pencil',
        'attributes' => [
            'class' => 'btn btn-edit',
            'href' => 'igniter/mealplan/mealplan/edit/{id}',
        ],
    ],
    'name' => [
        'label' => 'Plan Name',
        'searchable' => true
    ],
    'status' => [
        'label' => 'lang:admin::lang.label_status',
        'type' => 'switch',
        'onText' => 'lang:igniter.local::default.reviews.text_approved',
        'offText' => 'lang:igniter.local::default.reviews.text_pending_review',
    ],
    'created_at' => [
        'label' => 'lang:admin::lang.column_date_added',
        'type' => 'timetense',
    ]
];

$config['form']['toolbar'] = [
    'buttons' => [
        'back' => [
            'label' => 'lang:admin::lang.button_icon_back',
            'class' => 'btn btn-outline-secondary',
            'href' => 'igniter/mealplan/reviews',
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


$config['form']['tabs'] = [
    'defaultTab' => 'lang:admin::lang.menus.text_tab_general',
    'fields' => [
        'name' => [
            'label' => 'lang:admin::lang.label_name',
            'type' => 'text',
            'span' => 'left',
        ],
        'price' => [
            'label' => 'lang:admin::lang.menus.label_price',
            'type' => 'currency',
            'span' => 'right',
            'cssClass' => 'flex-width',
        ],
        'discount' => [
            'label' => 'discount',
            'type' => 'currency',
            'span' => 'right',
            'default' => 0.00,
            'cssClass' => 'flex-width',
        ],
        'description' => [
            'label' => 'Description',
            'type' => 'textarea',
            'span' => 'left',
            'attributes' => [
                'rows' => 5,
            ],
        ],
        'short_description' => [
            'label' => 'Short description',
            'type' => 'textarea',
            'span' => 'right',
            'attributes' => [
                'rows' => 5,
            ],
        ],
        'thumb' => [
            'label' => 'lang:admin::lang.menus.label_image',
            'type' => 'mediafinder',
            'comment' => 'lang:admin::lang.menus.help_image',
            'span' => 'left',
            'useAttachment' => true,
        ],
        'status' => [
            'label' => 'lang:admin::lang.label_status',
            'type' => 'switch',
            'default' => 1,
            'span' => 'right',
        ],
        '_options' => [
            'label' => 'Addon List',
            'tab' => 'lang:admin::lang.menus.text_tab_menu_option',
            'type' => 'recordeditor',
            'context' => ['edit', 'preview'],
            'form' => 'mealplanoption',
            'modelClass' => 'Igniter\MealPlan\Models\MealPlanOption',
            'placeholder' => 'lang:admin::lang.menus.help_menu_option',
            'formName' => 'lang:admin::lang.menu_options.text_option',
            'popupSize' => 'modal-xl',
            'addonRight' => [
                'label' => '<i class="fa fa-long-arrow-down"></i> Add to Menu',
                'tag' => 'button',
                'attributes' => [
                    'class' => 'btn btn-default',
                    'data-control' => 'choose-record',
                    'data-request' => 'onChooseMenuOption',
                ],
            ],
        ],
        'mealplan_addon' => [
            'label' => 'MealPlan Addons',
            'tab' => 'lang:admin::lang.menus.text_tab_menu_option',
            'type' => 'connector',
            'partial' => 'form/mealplan_addons',
            'nameFrom' => 'mealplan_addon',
            'formName' => 'lang:admin::lang.menu_options.text_form_name',
            'form' => 'mealplanaddon',
            'popupSize' => 'modal-lg',
            'sortable' => true,
            'context' => ['edit', 'preview'],
        ]
    ],
];

return $config;
