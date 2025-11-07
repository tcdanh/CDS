<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectDetail extends Model
{
    use HasFactory;

    protected $table = 'Detail_projects';

    protected $fillable = [
        'project_id',
        'contract_number',
        'contract_signed_at',
        'contract_storage_path',
        'direct_labor_cost',
        'material_cost',
        'other_cost',
        'management_cost',
        'is_extended',
        'extension_details',
    ];

    protected $casts = [
        'contract_signed_at' => 'date',
        'direct_labor_cost' => 'decimal:2',
        'material_cost' => 'decimal:2',
        'other_cost' => 'decimal:2',
        'management_cost' => 'decimal:2',
        'is_extended' => 'boolean',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
