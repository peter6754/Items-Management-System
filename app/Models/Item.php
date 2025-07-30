<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

enum ItemStatus: string
{
    case Allowed = 'Allowed';
    case Prohibited = 'Prohibited';
}

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'status',
    ];

    protected $casts = [
        'status' => ItemStatus::class,
    ];

    public function scopeAllowed($query)
    {
        return $query->where('status', ItemStatus::Allowed);
    }
}
