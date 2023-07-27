<?php
$config['form']['fields'] = [

    'meal_plan_id' => [
        'type' => 'hidden',
    ],
    'meal_plan_option_id' => [
        'type' => 'hidden',
    ],
    'meal_plan_option[name]' => [
        'label' => 'lang:admin::lang.menus.label_option_name',
        'type' => 'text',
        'span' => 'left',
        'disabled' => true,
    ],
    'meal_plan_option[display_type]' => [
        'label' => 'lang:admin::lang.menus.label_option_display_type',
        'type' => 'text',
        'span' => 'right',
        'disabled' => true,
    ],
    'meal_plan_option_values' => [
        'type' => 'repeater',
        'form' => 'mealplanoptionvalue',
        'sortable' => true,
    ],
];

return $config;
