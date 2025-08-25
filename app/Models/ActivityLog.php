<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    // Relationship: Activity belongs to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Get the model instance
    public function getModelInstanceAttribute()
    {
        if ($this->model && $this->model_id) {
            $modelClass = 'App\\Models\\' . $this->model;
            if (class_exists($modelClass)) {
                return $modelClass::find($this->model_id);
            }
        }
        return null;
    }

    // Log activity helper
    public static function log($action, $model = null, $modelId = null, $oldValues = null, $newValues = null)
    {
        return self::create([
            'user_id' => auth()->id() ?? 1,
            'action' => $action,
            'model' => $model,
            'model_id' => $modelId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
