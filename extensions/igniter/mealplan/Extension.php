<?php 

namespace Igniter\MealPlan;

use System\Classes\BaseExtension;

use Igniter\Local\Classes\Location;
use Igniter\Flame\Geolite\Facades\Geocoder;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Main\Facades\Auth;

use Igniter\MealPlan\Models\MealPlan;
use Igniter\MealPlan\Models\MealPlanSettings;
/**
 * MealPlan Extension Information File
 */
class Extension extends BaseExtension
{
    /**
     * Register method, called when the extension is first registered.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('location', Location::class);

        $aliasLoader = AliasLoader::getInstance();
        $aliasLoader->alias('Location', Facades\Location::class);
        
        // $this->app->register(\Igniter\Flame\Cart\CartServiceProvider::class);
        // AliasLoader::getInstance()->alias('Cart', \Igniter\Flame\Cart\Facades\Cart::class);
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {
        // pass settings to view
        //Event::listen('router.beforeRoute', function ($url, $router) {
          //  View::share('showReviews', (bool)MealPlanSettings::get('allow_mealplan', false));
        //});
    }


    public function registerCartConditions()
    {
         return [
            \Igniter\MealPlan\CartConditions\Delivery::class => [
                'name' => 'delivery',
                'label' => 'lang:igniter.local::default.text_delivery',
                'description' => 'lang:igniter.local::default.help_delivery_condition',
            ],
        ];
    }

    // public function registerAutomationRules()
    // {
    //     return [
    //         'events' => [
    //             'admin.order.paymentProcessed' => \Igniter\Cart\AutomationRules\Events\OrderPlaced::class,
    //             'igniter.cart.orderStatusAdded' => \Igniter\Cart\AutomationRules\Events\NewOrderStatus::class,
    //             'igniter.cart.orderAssigned' => \Igniter\Cart\AutomationRules\Events\OrderAssigned::class,
    //         ],
    //         'actions' => [],
    //         'conditions' => [
    //             \Igniter\Cart\AutomationRules\Conditions\OrderAttribute::class,
    //             \Igniter\Cart\AutomationRules\Conditions\OrderStatusAttribute::class,
    //         ],
    //     ];
    // }

    /**
     * Registers any front-end components implemented in this extension.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            \Igniter\MealPlan\Components\LocalMealPlanMenu::class => [
                'code' => 'localMealPlanMenu',
                'name' => 'lang:igniter.local::default.menu.component_title',
                'description' => 'lang:igniter.local::default.menu.component_desc',
            ],
            \Igniter\MealPlan\Components\MealPlanCartBox::class => [
                'code' => 'mealPlanCartBox',
                'name' => 'lang:igniter.local::default.menu.component_title',
                'description' => 'lang:igniter.local::default.menu.component_desc',
            ],
            \Igniter\MealPlan\Components\MealPlanCheckout::class => [
                'code' => 'mealPlanCheckout',
                'name' => 'lang:igniter.local::default.menu.component_title',
                'description' => 'lang:igniter.local::default.menu.component_desc',
            ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
            //'igniter.local::mail.review_chase' => 'lang:igniter.local::default.reviews.text_chase_email',
        ];
    }

    public function registerNavigation()
    {
        return [
            'restaurant' => [
                'child' => [
                    'meal_plan' => [
                        'priority' => 30,
                        'class' => 'mealplan',
                        'href' => admin_url('igniter/mealplan/mealplan'),
                        'title' => 'MealPlan',
                       // 'permission' => 'Admin.MealPlan',
                    ],
                ],
            ],
            'sales' => [
                'child' => [
                    'meal_plan_orders' => [
                        'priority' => 10,
                        'class' => 'meal_plan_orders',
                        'href' => admin_url('igniter/mealplan/mealplanorders'),
                        'title' => 'MealPlan Orders',
                       // 'permission' => 'Admin.MealPlan',
                    ],
                ],
            ],
            'system' => [
                'child' => [
                    'meal_plan_driver' => [
                        'priority' => 20,
                        'class' => 'meal_plan_driver',
                        'href' => admin_url('igniter/mealplan/driverzone'),
                        'title' => 'Driver Route',
                       // 'permission' => 'Admin.MealPlan',
                    ],
                ],
            ],
        ];
    }

    /**
     * Registers any admin permissions used by this extension.
     *
     * @return array
     */
    public function registerPermissions()
    {
// Remove this line and uncomment block to activate
        return [
//            'Igniter.MealPlan.SomePermission' => [
//                'description' => 'Some permission',
//                'group' => 'module',
//            ],
        ];
    }


    public function registerSettings()
    {
        return [
            'mealplansettings' => [
                'label' => 'mealplansettings',
                'icon' => 'fa fa-gear',
                'description' => 'mealplansettings description',
                'model' => \Igniter\MealPlan\Models\MealPlanSettings::class,
                'permissions' => ['Admin.MealPlan'],
            ],
        ];
    }


}
