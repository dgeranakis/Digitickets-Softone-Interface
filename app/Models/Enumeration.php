<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Enumeration extends Model implements TranslatableContract
{
    use Translatable, LogsActivity;

    public $translatedAttributes = ['description'];
    protected $fillable = ['domain_id', 'code', 'active', 'created_by_user', 'updated_by_user'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['id', 'domain.code', 'code', 'active']);
        //->logOnlyDirty();
    }

    /**
     * Scope a query to include enumerations by domain_code.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string                                 $domain_code
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByDomain($query, $domain_code)
    {
        $domain = Domain::where('code', $domain_code)->first();
        if ($domain) {
            $query->where('domain_id', $domain->id)->orderByTranslation('description')
                ->select('enumerations.id', 'enumeration_translations.description as value', 'enumerations.active');
        }
    }

    /**
     * Get the domain that owns the enumeration.
     */
    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
