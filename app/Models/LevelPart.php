<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LevelPart extends Model
{
    protected $fillable = [
        'level_case_id',
        'parts_no',
        'ruibe',
        'parts_name',
        'qty',
        'unit_weight',
        'net_weight',
        'fta_code',
    ];

    public function levelCase(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LevelCase::class);
    }
}
