@foreach ($optionValues as $optionValue)
    <div @class(['form-check', 'py-2' => !$loop->first || !$loop->last])>
        <input
            type="radio"
            id="menuOptionRadio{{ $menuOptionValueId = $optionValue->id }}"
            class="form-check-input"
            name="menu_options[{{ $index }}][option_values][]"
            value="{{ $optionValue->id }}"
            data-option-price="{{ $optionValue->price }}"
            @if (($cartItem && $cartItem->hasOptionValue($menuOptionValueId)) )
            checked="checked"
            @endif
        >

        <label
            class="form-check-label ps-2 w-100"
            for="menuOptionRadio{{ $menuOptionValueId }}"
        >
            {{ $optionValue->value }}
            @if ($optionValue->price > 0 || !$hideZeroOptionPrices)
                <span class="float-end fw-light">@lang('main::lang.text_plus'){{ currency_format($optionValue->price) }}</span>
            @endif
        </label>
    </div>
@endforeach
