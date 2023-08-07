<?php

namespace Igniter\MealPlan\Database\Migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Igniter\MealPlan\Models\MealPlanSettings;

class CreateMealPlanSchema extends Migration
{
    public function up()
    {
        if (Schema::hasTable('meal_plans'))
            return;

        Schema::create('meal_plans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 164);
            $table->string('description', 164)->nullable();
            $table->string('short_description', 164)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 15, 4)->nullable();
            $table->string('duration')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();
        });

        Schema::create('meal_plan_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
            $table->string('display_type');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
        Schema::create('meal_plan_option_values', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('value');
            $table->integer('meal_plan_option_id');
            $table->integer('priority')->default(0);
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('meal_plan_addons', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('meal_plan_id')->nullable();
            $table->integer('meal_plan_option_id')->nullable();
            $table->boolean('required')->default(1);
            $table->integer('priority')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });


        Schema::create('meal_plan_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('hash', 40)->nullable()->index();
            $table->integer('customer_id')->nullable();
            $table->string('first_name', 32);
            $table->string('last_name', 32);
            $table->string('email', 96);
            $table->string('telephone', 32);
            $table->integer('location_id');
            $table->integer('address_id')->nullable();
            $table->text('cart');
            $table->integer('total_items');
            $table->text('comment');
            $table->string('payment', 35);
            $table->string('order_type', 32);
            $table->dateTime('date_added');
            $table->date('date_modified');
            $table->time('order_time');
            $table->date('order_date');
            $table->decimal('order_total', 15, 4)->nullable();
            $table->integer('status_id');
            $table->string('ip_address', 40);
            $table->string('user_agent');
            $table->boolean('notify');
            $table->integer('assignee_id');
            $table->integer('invoice_no');
            $table->string('invoice_prefix', 32);
            $table->dateTime('invoice_date');
            $table->boolean('processed')->nullable();
            $table->text('delivery_comment')->nullable();
            $table->integer('area_id')->nullable();
            $table->timestamps();
        });
    
        // will have meal plan item id
        Schema::create('meal_plan_order_items', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('meal_plan_id');
            $table->string('name');
            $table->integer('quantity');
            $table->decimal('price', 15, 4)->nullable();
            $table->decimal('subtotal', 15, 4)->nullable();
            $table->text('option_values');
            $table->text('comment');
        });
        // will have meal plan item options
        Schema::create('meal_plan_order_item_options', function (Blueprint $table) {
            $table->id();
            $table->integer('meal_plan_order_item_id');
            $table->integer('order_id');
            $table->integer('meal_plan_id');
            $table->integer('meal_plan_order_option_id');
            $table->integer('meal_plan_option_value_id');
            $table->string('order_option_name', 128);
            $table->decimal('order_option_price', 15, 4)->nullable();
            $table->string('order_option_value_name', 128);
            $table->decimal('order_option_value_price', 15, 4)->nullable();
            $table->integer('quantity');
        });
        
        // order total with delivery an taxes
        Schema::create('meal_plan_order_totals', function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->id();
                $table->integer('order_id');
                $table->string('code', 30);
                $table->string('title');
                $table->decimal('value', 15);
                $table->boolean('priority');
                $table->boolean('is_summable')->default(0);
        });

        Schema::create('meal_plan_order_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('meal_plan_order_id');
            $table->integer('customer_id');
            $table->integer('staff_id');
            $table->integer('delivered_at');
            $table->string('image');
            $table->float('lat', 10, 6);
            $table->float('lng', 10, 6);
            $table->text('metadata');
            $table->string('delivery_status');
            $table->timestamps();
        });

        // we can use location area zones
        Schema::create('driver_zones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('staff_id');
            $table->integer('area_id');
            $table->integer('status');
            $table->timestamps();
        });

        Schema::create('customer_zones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('customer_id');
            $table->integer('area_id');
            $table->integer('status');
            $table->timestamps();
        });

        Schema::create('meal_plan_payment_logs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('payment_log_id', true);
            $table->integer('meal_plan_order_id');
            $table->string('payment_name', 128);
            $table->string('message');
            $table->text('request')->nullable();
            $table->text('response')->nullable();
            $table->boolean('status')->default(0);
            $table->dateTime('date_added')->nullable();
            $table->dateTime('date_updated')->nullable();
            $table->string('payment_code');
            $table->boolean('is_refundable')->default(false);
            $table->dateTime('refunded_at')->nullable();
            $table->timestamps();
        });


        MealPlanSettings::set([
            'allow_reviews' => setting('allow_mealplan', '1'),
            'approve_reviews' => setting('approve_mealplan', '1')
        ]);

        //$this->seedCoupons();
    }

    public function down()
    {
        Schema::dropIfExists('meal_plans');
        Schema::dropIfExists('meal_plan_options');
        Schema::dropIfExists('meal_plan_option_values');
        Schema::dropIfExists('meal_plan_addons');
        
        Schema::dropIfExists('meal_plan_orders');
        Schema::dropIfExists('meal_plan_order_items');
        Schema::dropIfExists('meal_plan_order_item_options');
        Schema::dropIfExists('meal_plan_order_totals');

        Schema::dropIfExists('meal_plan_order_transactions');
        Schema::dropIfExists('driver_zones');
        Schema::dropIfExists('customer_zones');
        Schema::dropIfExists('meal_plan_payment_logs');
        
    }

    protected function seedCoupons()
    {
        if (DB::table('meal_plans')->count())
            return;

        $tables =[
            'meal_plans',
            'meal_plan_options',
            'meal_plan_orders',
            'meal_plan_order_options',
            'meal_plan_order_transection',
            'delivery_zones',
            'driver_zones'
        ];

        DB::table('meal_plans')->insert(array_map(function ($record) {
           // $record['order_restriction'] = 0;
           //$record['date_added'] = now();
            return $record;
        }, $this->getSeedRecords('meal_plans')));
    }

    protected function getSeedRecords($name)
    {
        return json_decode(file_get_contents(__DIR__.'/../records/'.$name.'.json'), true);
    }
}
