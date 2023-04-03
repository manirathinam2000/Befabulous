<?php

namespace App\MicroSite\DashBoard\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function getUserDetails()
    {
        try {
            $response = Http::microsite()->withHeaders([
                'cap_authorization' => request()->header('cap-authorization'),
                'cap_brand' => request()->header('cap-brand'),
                'cap_device_id' => request()->header('cap-device-id'),
                'cap_mobile' => request()->header('cap-mobile'),
            ])->get('/mobile/v2/api/customer/get', [
                'subscriptions' => 'true',
                'mlp' => 'true',
                'user_id' => 'true',
                'optin_status' => 'true',
                'slab_history' => 'true',
                'expired_points' => 'true',
                'points_summary' => 'true',
                'membership_retention_criteria' => 'true',
            ]);
            $json = $response->json();
            if ($json['status']['success'] == false) {
                if ($json['status']['code'] == 401) {
                    return to_route('login_page')->with('false', 'The Session has been expired!');
                }
                return ['success' => false, 'data' => 'Something went wrong!'];
            }
            $customer = $json['customers']['customer'][0] ?? [];
            $user_id = $json['customers']['customer'][0]['user_id'] ?? '';
            if ($user_id != null) {
                $customer['profile'] = User::where('user_id', $user_id)->first()->profile ?? '';
            }
            return ['success' => true, 'data' => $customer];
        } catch (\Exception$e) {
            return ['success' => false, 'data' => 'Something went wrong!'];
        }

    }

    public function getUserCoupons($status_value = '')
    {
        try {
            $status = 'active';
            if ($status_value != null) {
                $status = $status_value;
            }
            $response = Http::microsite()->withHeaders([
                'cap_authorization' => request()->header('cap-authorization'),
                'cap_brand' => request()->header('cap-brand'),
                'cap_device_id' => request()->header('cap-device-id'),
                'cap_mobile' => request()->header('cap-mobile'),
            ])->get('/mobile/v2/api/customer/coupon', [
                'status' => $status,
            ]);
            $json = $response->json()['response'];
            if ($json['status']['success'] == false) {
                if ($json['status']['code'] == 302) {
                    return to_route('login_page')->with('false', 'The Session has been expired!');
                }
                if ($json['status']['code'] == 401) {
                    return abort(404);
                }
                return ['success' => false, 'data' => 'Something went wrong!'];
            }
            $coupons = $json['customers']['customer'][0]['coupons']['coupon'] ?? [];
            return ['success' => true, 'data' => $coupons];
        } catch (\Throwable$th) {
            return ['success' => false, 'data' => 'Something went wrong!'];
        }

    }

    public function getUserPointHistory($start_date = '', $end_date = '')
    {
        try {
            $response = Http::microsite()->withHeaders([
                'cap_authorization' => request()->header('cap-authorization'),
                'cap_brand' => request()->header('cap-brand'),
                'cap_device_id' => request()->header('cap-device-id'),
                'cap_mobile' => request()->header('cap-mobile'),
            ])->get('/mobile/v2/api/points/history', [
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);
            $json = $response->json();
            if ($json['status']['success'] == false) {
                if ($json['status']['code'] == 302) {
                    return ['success' => false, 'data' => 'Session has been expired!'];
                }
                if ($json['status']['code'] == 401) {
                    return ['success' => false, 'data' => 'Session has been expired!'];
                }
                return ['success' => false, 'data' => 'Something went wrong!'];
            }

            $customer_points = $json['customer'] ?? [];
            return ['success' => true, 'data' => $customer_points];
        } catch (\Exception$e) {
            return ['success' => false, 'data' => 'Something went wrong!'];
        }

    }

    public function updateUserDetails($request)
    {
        DB::beginTransaction();
        try {

            $user_data = [
                "root" => [
                    "customer" => [
                        [
                            "firstname" => $request->firstname,
                            "lastname" => $request->lastname,
                            "email" => $request->email,
                            "extended_fields" => [
                                "field" => [
                                    [
                                        "name" => "city",
                                        "value" => $request->city,
                                    ],
                                    [
                                        "name" => "State",
                                        "value" => $request->State,
                                    ],
                                    [
                                        "name" => "country_of_residence",
                                        "value" => $request->country_of_residence,
                                    ],
                                    [
                                        "name" => "gender",
                                        "value" => $request->gender,
                                    ],
                                    [
                                        "name" => "zip",
                                        "value" => $request->zip,
                                    ],
                                    [
                                        "name" => "dob",
                                        "value" => $request->dob,
                                    ],
                                    [
                                        "name" => "wedding_date",
                                        "value" => $request->wedding_date,
                                    ],
                                    [
                                        "name" => "marital_status",
                                        "value" => $request->marital_status,
                                    ],
                                    [
                                        "name" => "area",
                                        "value" => $request->area
                                    ],
                                    [
                                        "name" => "nationality",
                                        "value" => $request->nationality
                                    ],
                                    [
                                        "name" => "religion",
                                        "value" => $request->religion
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];
            $response = Http::microsite()->withHeaders([
                'cap_authorization' => request()->header('cap-authorization'),
                'cap_brand' => request()->header('cap-brand'),
                'cap_device_id' => request()->header('cap-device-id'),
                'cap_mobile' => $request->mobile ?? request()->header('cap-mobile'),
            ])->post('/mobile/v2/api/customer/update', $user_data);
            $json = $response->json();
            if ($json['status']['success'] == false) {
                if ($json['status']['code'] == 401) {
                    return ['success' => false, 'data' => 'Session has been Expired!'];
                }
                return ['success' => false, 'data' => 'Something went wrong!'];
            }
            if ($request->hasFile('profile') && isset($json['customers']['customer'][0]['user_id'])) {
                $user_id = $json['customers']['customer'][0]['user_id'];
                $user = User::where('user_id', $user_id);
                if ($user->first()) {
                    if (\File::exists(public_path('/storage/' . $user->first()->profile))) {
                        \File::delete(public_path('/storage/' . $user->first()->profile));
                    }
                    $profile_path = Storage::disk('public')->put('profiles', request()->file('profile'));
                    $user->update(['profile' => $profile_path]);
                } else {
                    $profile_path = request()->file('profile')->store('public/profile');
                    $user = new User();
                    $user->user_id = $user_id;
                    $user->profile = $profile_path;
                    $user->save();
                }
            }
            $customer = $json['customers']['customer'][0] ?? [];
            DB::commit();
            return ['success' => true, 'data' => $customer];
        } catch (\Exception$e) {
            DB::rollBack();
            return ['success' => false, 'data' => 'Something went wrong!'];
        }
    }

    public function validate()
    {
        $attributes = request()->validate([
            "firstname" => 'required',
            "lastname" => 'required',
            "email" => 'required',
            "mobile" => 'required',
            "password" => 'required|confirmed',
            'password_confirmation' => 'required',
            "dob" => "nullable|date",
            "age" => 'nullable|numeric',
            "area" => 'nullable',
            "city" => 'nullable',
            "state" => 'nullable',
            "zip" => 'nullable',
            "country_of_residence" => 'nullable',
            "gender" => "required",
            "wedding_date" => "nullable|date",
            "marital_status" => "nullable",
            "nationality" => 'nullable',
            "religion" => 'nullable',
        ]);

        return $attributes;
    }

    public function updateValidate()
    {
        $attributes = request()->validate([
            "firstname" => 'required',
            "lastname" => 'required',
            "email" => 'required',
            "mobile" => 'required',
            "dob" => "nullable|date",
            "age" => 'nullable|numeric',
            "area" => 'nullable',
            "city" => 'nullable',
            "state" => 'nullable',
            "zip" => 'nullable',
            "country_of_residence" => 'nullable',
            "gender" => "required",
            "wedding_date" => "nullable|date",
            "marital_status" => "nullable",
            "nationality" => 'nullable',
            "religion" => 'nullable',
        ]);

        return $attributes;
    }
}
