<?php
// ============================================================
// Portfolio Model — app/Models/Portfolio.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'category', 'client', 'description', 'project_url',
        'year', 'tags', 'thumbnail', 'order', 'is_active', 'is_featured',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'year'        => 'integer',
        'order'       => 'integer',
    ];
}
