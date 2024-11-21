<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EnumerationTranslation extends Model
{
    use LogsActivity;
    public $timestamps = false;
    protected $fillable = ['description'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id', 'enumeration_id', 'description', 'locale']);
        //->logOnlyDirty();
    }
}
