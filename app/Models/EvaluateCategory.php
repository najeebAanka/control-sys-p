<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class EvaluateCategory
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property float|null $min_score
 * @property float|null $max_score
 * @property float|null $normal_min_score
 * @property float|null $normal_max_score
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|HorsePoint[] $horse_points
 *
 * @package App\Models
 */
class EvaluateCategory extends Model
{

    use HasFactory;

	protected $table = 'evaluate_categories';

	protected $casts = [
		'min_score' => 'float',
		'max_score' => 'float',
		'normal_min_score' => 'float',
		'normal_max_score' => 'float'
	];

	protected $fillable = [
		'name',
		'code',
		'min_score',
		'max_score',
		'normal_min_score',
		'normal_max_score'
	];

	public function horse_points()
	{
		return $this->hasMany(HorsePoint::class, 'category_id');
	}
}
