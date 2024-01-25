<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HorsePoint
 * 
 * @property int $id
 * @property int|null $reg_id
 * @property int|null $horse_id
 * @property int|null $judge_id
 * @property int|null $category_id
 * @property float|null $score
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property EvaluateCategory|null $evaluate_category
 * @property HorseRegistration|null $horse_registration
 * @property User|null $user
 *
 * @package App\Models
 */
class HorsePoint extends Model
{
	protected $table = 'horse_points';

	protected $casts = [
		'reg_id' => 'int',
		'horse_id' => 'int',
		'judge_id' => 'int',
		'category_id' => 'int',
		'score' => 'float'
	];

	protected $fillable = [
		'reg_id',
		'horse_id',
		'judge_id',
		'category_id',
		'score'
	];

	public function evaluate_category()
	{
		return $this->belongsTo(EvaluateCategory::class, 'category_id');
	}

	public function horse_registration()
	{
		return $this->belongsTo(HorseRegistration::class, 'reg_id');
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'judge_id');
	}
}
