
@foreach ($menuItem->mealplan_addon as $index => $mealPlanAddon)

    <div
        class="menu-option"
        data-control="item-option"
        data-option-type="{{ $mealPlanAddon->meal_plan_option->display_type }}"
        data-option-minimum="{{ 1 }}"
        data-option-maximum="{{ 8 }}"
    >
        <div class="option option-{{ $mealPlanAddon->meal_plan_option->display_type }}">
            <div class="option-details">
                <h5 class="mb-0">
                    {{ $mealPlanAddon->meal_plan_option->name }}
                    @if ( isset($mealPlanAddon->meal_plan_option->required ) && $mealPlanAddon->meal_plan_option->required == 1)
                        <span
                            class="small pull-right text-muted">@lang('igniter.cart::default.text_required')</span>
                    @endif
                </h5>
                @if (1 > 0 || 8 > 0)
                    <p class="mb-0">{!! sprintf(lang('igniter.cart::default.text_option_summary'), $mealPlanAddon->meal_plan_option->min_selected, $mealPlanAddon->meal_plan_option->max_selected) !!}</p>
                @endif
            </div>

            @if (count($optionValues = $mealPlanAddon->meal_plan_option->meal_plan_option_values))
                <input
                    type="hidden"
                    name="menu_options[{{ $index }}][meal_plan_option_id]"
                    value="{{ $mealPlanAddon->meal_plan_option->id }}"
                />
                <div class="option-group">
                    @partial('@item_option_'.$mealPlanAddon->meal_plan_option->display_type, [
                        'index' => $index,
                        'cartItem' => $cartItem,
                        'optionValues' => $optionValues->sortBy('value'),
                    ])
                </div>
            @endif
        </div>
    </div>
@endforeach
