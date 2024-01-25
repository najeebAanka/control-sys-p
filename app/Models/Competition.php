<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Competition
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $logo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $score_calc_type
 * 
 * @property Collection|CompetitionGroup[] $competition_groups
 *
 * @package App\Models
 */
class Competition extends Model
{
	protected $table = 'competitions';

	protected $fillable = [
		'name_en',
		'name_ar',
		'description_en',
		'description_ar',
		'logo',
		'score_calc_type',
		'active_phase',
		'prize_report_header',
		'prize_report_footer',
		'prize_owner_name',
		'prize_owner_description',
		'prize_currency',
	];

	public function competition_groups()
	{
		return $this->hasMany(CompetitionGroup::class);
	}
        public function buildStorageBase(){
            return url('storage/competitions');
        }
}
