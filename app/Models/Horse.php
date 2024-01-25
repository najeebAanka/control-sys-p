<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Horse
 * 
 * @property int $id
 * @property string|null $reg_no_en
 * @property string|null $reg_no_ar
 * @property string|null $class_en
 * @property string|null $class_ar
 * @property string|null $name_en
 * @property string|null $name_ar
 * @property string|null $gender
 * @property string|null $color_en
 * @property string|null $color_ar
 * @property Carbon|null $dob
 * @property string|null $sire_name_en
 * @property string|null $sire_name_ar
 * @property string|null $dam_en
 * @property string|null $dam_ar
 * @property string|null $stud_name_en
 * @property string|null $stud_name_ar
 * @property string|null $owner_name_en
 * @property string|null $owner_name_ar
 * @property string|null $breeder_name_en
 * @property string|null $breeder_name_ar
 * @property string|null $owner_country_name_en
 * @property string|null $owner_country_name_ar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|HorseRegistration[] $horse_registrations
 *
 * @package App\Models
 */
class Horse extends Model
{
	protected $table = 'horses';

	protected $dates = [
		'dob'
	];

	protected $fillable = [
		'reg_no_en',
		'reg_no_ar',
		'class_en',
		'class_ar',
		'name_en',
		'name_ar',
		'gender',
		'color_en',
		'color_ar',
		'dob',
		'sire_name_en',
		'sire_name_ar',
		'dam_en',
		'dam_ar',
		'stud_name_en',
		'stud_name_ar',
		'owner_name_en',
		'owner_name_ar',
		'breeder_name_en',
		'breeder_name_ar',
		'owner_country_name_en',
		'owner_country_name_ar'
	];

	public function horse_registrations()
	{
		return $this->hasMany(HorseRegistration::class);
	}
}
