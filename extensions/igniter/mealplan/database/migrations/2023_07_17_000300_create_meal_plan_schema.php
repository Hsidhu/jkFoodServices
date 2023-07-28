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
            $table->integer('meal_plan_id');
            $table->integer('customer_id');
            $table->string('note', 164);
            $table->decimal('delivery_charge', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('period_start_date')->nullable();
            $table->date('period_end_date')->nullable();
            $table->boolean('recurring')->nullable();
            $table->string('recurring_every', 30)->nullable();
            $table->string('ip_address', 40);
            $table->string('user_agent');
            $table->string('payment', 35);
            $table->string('order_type', 32);
            $table->text('cart');
            $table->integer('invoice_no');
            $table->string('invoice_prefix', 32);
            $table->dateTime('invoice_date');
            $table->text('staff_comment')->nullable();
            $table->text('customer_comment')->nullable();
            $table->date('cancelled_date')->nullable();
            $table->string('status');
            $table->timestamps();
        });

        Schema::create('meal_plan_order_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->integer('meal_plan_order_id');
            $table->integer('meal_plan_addon_id');
            $table->integer('meal_plan_option_id');
            $table->integer('meal_plan_option_value_id');
            $table->string('name');
            $table->text('metadata');
            $table->decimal('total_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->timestamps();
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
        Schema::dropIfExists('meal_plan_order_options');
        Schema::dropIfExists('meal_plan_order_transactions');
        Schema::dropIfExists('driver_zones');
        Schema::dropIfExists('customer_zones');
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
