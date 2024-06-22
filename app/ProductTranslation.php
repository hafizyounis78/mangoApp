<?php
namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductTranslation extends Model{
	
    protected $table = 'product_translations';
	protected $primaryKey = 'ptr_id';
	public $timestamps = false;
	
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'prd_id',
		'ptr_name',
		'ptr_description',
		'lng_id',
	];
	
	
	public function product(){
		return $this->belongsTo('App\Product', 'prd_id', 'prd_id');
	}
}