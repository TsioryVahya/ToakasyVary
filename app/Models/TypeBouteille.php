<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeBouteille extends Model
{
    protected $table = 'type_bouteilles';
    
    protected $fillable = [
        'capacite'
    ];
}