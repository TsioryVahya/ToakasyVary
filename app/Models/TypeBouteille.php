<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeBouteille extends Model
{
    protected $table = 'type_bouteilles';
    protected $fillable = ['nom', 'capacite'];
    public $timestamps = true;

    public function lots()
    {
        return $this->hasMany(LotProduction::class, 'id_bouteille');
    }
}