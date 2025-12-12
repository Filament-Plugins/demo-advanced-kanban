<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_path',
    ];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
