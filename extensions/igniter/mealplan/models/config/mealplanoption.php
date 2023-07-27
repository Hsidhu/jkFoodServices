<?php
$config['form']['fields'] = [
    'name' => [
        'label' => 'lang:admin::lang.menu_options.label_option_name',
        'type' => 'text',
        'span' => 'left',
    ],
    'display_type' => [
        'label' => 'Display Type',
        'type' => 'radiotoggle',
        'default' => 'radio',
    ],
    'values' => [
        'label' => 'Option Values',
        'type' => 'repeater',
        'valueFrom' => 'meal_plan_option_values', // relation or any method to get data
        'form' => 'mealplanoptionvalue', // config file name
        'sortable' => true,
    ],
    'status' => [
        'label' => 'lang:admin::lang.label_status',
        'type' => 'switch',
        'default' => 1,
        'span' => 'right',
    ]
];

$config['form']['rules'] = [
    ['name', 'lang:admin::lang.menu_options.label_option_name', 'required|min:2|max:32'],
    ['display_type', 'lang:admin::lang.menu_options.label_display_type', 'required'],
];

return $config;
