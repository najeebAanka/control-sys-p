<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ClassPrize;
use Dotenv\Parser\Value;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClassPrizeController extends Controller
{

	public function fetchAll($comp ,$class)
	{
		$data = ClassPrize::where('competition_id', $comp)->where('class_id', $class)->orderBy('id', 'desc')->paginate(25);
		return response()->json($data, 200);
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'competition_id' => 'required',
			'class_id' => 'required',
			'rank' => 'required',
			'amount' => 'required',
			'discount_fee' => 'required',
			
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$data = \App\Models\ClassPrize::updateOrCreate(
			['class_id' => $request->class_id,
			 'competition_id' => $request->competition_id,
			 'rank' => $request->rank 
			],
			['competition_id' => $request->competition_id,
			 'class_id' => $request->class_id,
			 'rank' => $request->rank,
			 'amount' => $request->amount,
			 'discount_fee' => $request->discount_fee,
			 'target_type' => $request->target_type
			 ]
		);
		$data->save();
		return back()->with('msg', 'Added succesfully');


//----------------------------
		// $checkdata = \App\Models\ClassPrize::where('rank', $request->rank)->where('class_id', $request->class_id)->where('competition_id', $request->competition_id)->first();
        // if ($checkdata) {

        //     $data_ = ClassPrize::find($checkdata->id);

        //     $data_->competition_id = $request->competition_id;
        //     $data_->class_id = $request->class_id;
        //     $data_->rank = $request->rank;
        //     $data_->amount = $request->amount;
        //     $data_->discount_fee = $request->discount_fee;
        //     $data_->target_type = $request->target_type;

        //     $data_->save();
        //     return back()->with('msg', 'Updated succesfully');

        // } else {

        //     $data = new ClassPrize();

        //     $data->competition_id = $request->competition_id;
        //     $data->class_id = $request->class_id;
        //     $data->rank = $request->rank;
        //     $data->amount = $request->amount;
        //     $data->discount_fee = $request->discount_fee;
		// 	$data->target_type = $request->target_type;

        //     $data->save();
        //     return back()->with('msg', 'Added succesfully');
        // }

//-------------------------------

		// $dData = [
		// 	'competition_id' => $request->competition_id,
		// 	'class_id' => $request->class_id,
		// 	'rank' => $request->rank,
		// 	'amount' => $request->amount,
		// 	'discount_fee' => $request->discount_fee,
			
		// ];

		// ClassPrize::create($dData);
		// // return response()->json([
		// // 	'status' => 200,
		// // ]);
		// return back()->with('msg'  ,'Added succesfully');
	}


	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'competition_id' => 'required',
			'class_id' => 'required',
			// 'rank' => 'required',
			'amount' => 'required',
			'discount_fee' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}




		$d = ClassPrize::find($request->id);
		if ($d) {
			$dData = [
				'competition_id' => $request->competition_id,
			'class_id' => $request->class_id,
			// 'rank' => $request->rank,
			'amount' => $request->amount,
			'discount_fee' => $request->discount_fee,
			];

			$d->update($dData);
			// return response()->json([
			// 	'status' => 200,
			// ]);
			return back()->with('msg'  ,'Updated succesfully');
		} else {
			// return response()->json([
			// 	'message' => $request->id . ' Not found',
			// 	'status' => 401,
			// ]);
			return back()->with('error'  ,'Not found');
		}
	}

	public function delete(Request $request)
	{
		$id = $request->id;

		ClassPrize::destroy($id);

		return back()->with('msg'  ,'Deleted succesfully');
	}
}