<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Directive
 * @package App\Models
 *
 * @property string movements
 * @property Date created_at
 * @property Date updated_at
 */
class Directive extends Model
{
    public $table = 'directives';
    public $timestamps = true;

    public $fillable = ['movements'];
    public $casts = [
        'id' => 'integer',
        'state' => 'string',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function completedClients() {
        return $this->belongsToMany(Client::class);
    }
}
