<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompetitionGroup
 * 
 * @property int $id
 * @property string|null $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $competition_id
 * @property int|null $status
 * @property int|null $current_horse
 * 
 * @property Competition|null $competition
 * @property Collection|HorseRegistration[] $horse_registrations
 *
 * @package App\Models
 */
class CompetitionGroup extends Model
{
    
    
    public function notifyJudges(){
        
      $list = ClassJudge::where('class_id' ,$this->id)->get();
      foreach ($list as $ls){
          User::find($ls->judge_id)->sendNotification(-1, -1, "Status changed", null);
      }
    }
    
	protected $table = 'competition_groups';

	protected $casts = [
		'competition_id' => 'int',
		'status' => 'int',
		'current_horse' => 'int'
	];

	protected $fillable = [
		'name_en',
		'name_ar',
		'competition_id',
		'status',
		'current_horse',
		'start_dob',
		'end_dob',
		'max_in_section',
		'gender',
	];

	public function competition()
	{
		return $this->belongsTo(Competition::class);
	}

	public function horse_registrations()
	{
		return $this->hasMany(HorseRegistration::class, 'group_id');
	}
}
