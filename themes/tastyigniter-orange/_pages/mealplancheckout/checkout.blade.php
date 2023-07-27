---
title: main::lang.checkout.title
layout: default
permalink: /mealplan/checkout

'[account]':

'[localSearch]':
    hideSearch: 1

'[localBox]':
    paramFrom: location
    showLocalThumb: 0

'[mealPlanCartBox]':
    pageIsCheckout: true

'[mealPlanCheckout]':
    showCountryField: 0
---
<div class="container">
    <div class="row py-4">
        <div class="col col-lg-8">
            @partial('localBox::container')

            <div class="card my-1">
                <div class="card-body">
                    @partial('account/welcome')
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    @component('mealPlanCheckout')
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            @partial('mealPlanCartBox::container')
        </div>
    </div>
</div>
