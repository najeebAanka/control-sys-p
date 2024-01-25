<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


class ClassPrize extends Model
{

	protected $table = 'classes_prizes';

	protected $fillable = [
		'class_id',
		'competition_id',
		'rank',
		'amount',
		'discount_fee',
		'target_type',
		
	];


}
