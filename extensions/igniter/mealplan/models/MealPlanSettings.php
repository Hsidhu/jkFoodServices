<?php

namespace Igniter\MealPlan\Models;

use Igniter\Flame\Database\Model;

class MealPlanSettings extends Model
{
    public $implement = [\System\Actions\SettingsModel::class];

    // A unique code
    public $settingsCode = 'igniter_mealplan_settings';

    // Reference to field configuration
    public $settingsFieldsConfig = 'mealplansettings';
}
