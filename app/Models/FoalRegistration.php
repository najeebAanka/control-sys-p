<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HorseRegistration
 * 
 * @property int $id
 * @property int|null $horse_id
 * @property int|null $group_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property float|null $total_marks
 * @property float|null $total_points
 * 
 * @property CompetitionGroup|null $competition_group
 * @property Horse|null $horse
 * @property Collection|HorsePoint[] $horse_points
 *
 * @package App\Models
 */
class FoalRegistration extends Model
{
//    
//    
//    public function getRanking(){
//   $collection = collect(HorseRegistration::where('total_points' ,'>' ,0)
//           ->where('group_id' ,$this->group_id)
//           ->where('sectionLabel' ,$this->sectionLabel)
//
//
//
//           
//           ->orderBy('total_points' ,'DESC') // tie
//           ->orderBy('total_c1', 'DESC') ///tie
//       ->orderBy('total_c2', 'DESC')
//      ->orderBy('judge_selection', 'DESC')
//            
//         //   ->orderByRaw('total_points DESC ,total_c1 DESC ,total_c2 DESC ,judge_selection ASC') // tie  
//           
//           ->get());
//   
//   
//   $data       = $collection->where('id', $this->id);
//   $value      = $data->keys()->first() + 1;
//   return $value;
//}
//    
	protected $table = 'foals_registrations';
//
//	protected $casts = [
//		'horse_id' => 'int',
//		'group_id' => 'int',
//		'total_marks' => 'float',
//		'total_points' => 'float'
//	];
//
//	protected $fillable = [
//		'horse_id',
//		'group_id',
//		'total_marks',
//		'total_points'
//	];
//
//	public function competition_group()
//	{
//		return $this->belongsTo(CompetitionGroup::class, 'group_id');
//	}
//
//	public function horse()
//	{
//		return $this->belongsTo(Horse::class);
//	}
//
//	public function horse_points()
//	{
//		return $this->hasMany(HorsePoint::class, 'reg_id');
//	}
}
