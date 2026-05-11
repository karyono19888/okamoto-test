<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelCase extends Model
{
    protected $fillable = [
        'container_id',
        'model',
        'o_f',
        'lot_no',
        'case_no',
    ];

    public function container(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Container::class);
    }

    public function parts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LevelPart::class);
    }
}
