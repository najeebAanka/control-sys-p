<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ChampionClass extends Model
{
	use SoftDeletes;

	protected $table = 'champion_classes';

	protected $fillable = [
		'name_en',
		'name_ar',
		// 'competition_id',
		'status',
		'start_dob',
		'end_dob',
		'gender',
		'age_info'
	];


	public function notifyJudges()
	{

		//      $list = ClassJudge::where('class_id' ,$this->id)->get();
		//      foreach ($list as $ls){{
		foreach (\App\Models\User::where('user_type', 0)->get() as $judge) {
			$judge->sendNotification(-1, -1, "Status changed", null);
		}
	}
}
