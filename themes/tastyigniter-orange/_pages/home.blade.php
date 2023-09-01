---
title: 'main::lang.home.title'
permalink: /
description: ''
layout: default

'[slider]':
    code: home-slider

'[featuredItems]':
    items: ['6', '7', '8', '9']
    limit: 3
    itemsPerRow: 3
    itemWidth: 400
    itemHeight: 300
---
@component('slider')


<section class="round pt-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-12 my-4 text-center">
                <a href="/default/mealplan">
                    <img src="{{ asset('themes/tastyigniter-orange/assets/images/tiffin_shape.jpeg') }}" class="img-fluid">
                    <h3 class="text-center bilbo-swash-font mt-3 text-cherry-red">Tiffin Delivery</h3>
                </a>
            </div>

            <div class="col-lg-4 col-12 my-4 text-center">
                <a href="default/menus">
                    <img src="{{ asset('themes/tastyigniter-orange/assets/images/take_away_shape.jpeg') }}" class="img-fluid">
                    <h3 class="text-center bilbo-swash-font mt-3 text-cherry-red">Take Away</h3>
                </a>
            </div>

            <div class="col-lg-4 col-12 my-4 text-center">
                <a href="default/menus">
                    <img src="{{ asset('themes/tastyigniter-orange/assets/images/catering_shape.jpeg') }}" class="img-fluid">
                    <h3 class="text-center bilbo-swash-font mt-3 text-cherry-red">Catering Delivery</h3>
                </a>
            </div>
            
        </div>
    </div>
</section>

@component('featuredItems')

<section class="bg-cherry-red">
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-4">
                <h6 class="text-white text-center">Submit Information Online to Book Your Upcoming Event</h6>
                <h4 class="font-weight-normal text-white text-center">Or Call us @ 
                    <span class="font-weight-bold text-white h3"></span>
                    <a class="text-decoration-none text-white" href="mailto:(123) 456 7890">(123) 456 7890</a>
                </h4>
            </div>
        </div>
    </div>
</section>