<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

	public function fetchAll()
	{
		$data = User::where('user_type', '!=', '1')->orderBy('id', 'desc')->paginate(25);
		return response()->json($data, 200);
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'password' => 'required',
			'country' => 'required',
			'order_label' => 'required',
			'gender' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$dData = [
			'name' => $request->name,
			'email' => $request->email,
			'order_label' => $request->order_label,
			'gender' => $request->gender,
			'password' => bcrypt($request->password),
			'qr_code' => md5(time()),
			'country' => $request->country,
			'status' => 1,
		];

		User::create($dData);
		return response()->json([
			'status' => 200,
		]);
	}


	public function update(Request $request)
	{

		$validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'password' => 'required',
			'qr_code' => 'required',
			'country' => 'required',
			'status' => 'required',
			'order_label' => 'required',
			'gender' => 'required',
		]);
		if ($validator->fails()) {
			return response()->json($this->failedValidation($validator), 400);
		}

		$d = User::find($request->id);
		if ($d) {
			$dData = [
				'name' => $request->name,
				'email' => $request->email,
				'password' => bcrypt($request->password),
				'qr_code' => $request->qr_code,
				'country' => $request->country,
				'status' => $request->status,
				'order_label' => $request->order_label,
				'gender' => $request->gender,
			];

			$d->update($dData);
			return response()->json([
				'status' => 200,
			]);
		} else {
			return response()->json([
				'message' => $request->id . ' Not found',
				'status' => 401,
			]);
		}
	}

	public function delete(Request $request)
	{
		$id = $request->id;

		User::destroy($id);
	}
}
