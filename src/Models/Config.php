<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $table = 'll_configs';

    public static function loadAllSettings()
    {
        foreach (self::all(['key', 'value']) as $config) {
            config([$config['key'] => $config['value']]);
        }
    }
}
