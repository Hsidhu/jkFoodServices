<?php

namespace Igniter\MealPlan\Requests;

use System\Classes\FormRequest;

class MealPlan extends FormRequest
{
    public function attributes()
    {
        return [
            'name' => 'Name',
            'description' => 'description',
            'short_description' => 'short_description',
            'price' => 'price',
            'discount' => 'discount',
            'status' => lang('admin::lang.label_status'),
        ];
    }

    public function rules()
    {
        return [
            'name' => ['required', 'between:2,255'],
            'description' => ['between:2,1028'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['numeric', 'min:0'],
            'status' => ['required', 'boolean'],
        ];
    }

    protected function prepareSaleIdExistsRule($parameters, $field)
    {
        return sprintf('exists:%s,%s_id', $this->inputWith('sale_type', ''), str_singular($this->inputWith('sale_type', '')));
    }
}
