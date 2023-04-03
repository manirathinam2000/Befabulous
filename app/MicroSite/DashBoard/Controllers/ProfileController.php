<?php

namespace App\MicroSite\DashBoard\Controllers;

use App\Http\Controllers\Controller;
use App\MicroSite\Auth\Services\AuthService;
use App\MicroSite\DashBoard\Services\UserService;
use App\MicroSite\Token\GenerateTokenService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $service;
    protected $generate_token;
    public function __construct()
    {
        $this->service = new UserService();
        $this->generate_token = new GenerateTokenService();
    }
    public function index()
    {
        try {
            $user_data = $this->service->getUserDetails();

            if ($user_data['success'] == false) {
                return $this->throwLogin();
            }
            return view('Profile.index', ['data' => $user_data['data']]);
        } catch (\Throwable$th) {
            return $this->throwLogin();
        }
    }

    public function edit()
    {
        try {
            $user_data = $this->service->getUserDetails();
            if (isset($user_data['success']) && ($user_data['success'] == false)) {
                return $this->throwLogin();
            }
            $data = [
                'firstname' => $user_data['data']['firstname'] ?? '',
                'lastname' => $user_data['data']['lastname'] ?? '',
                'mobile' => $user_data['data']['mobile'] ?? '',
                'email' => $user_data['data']['email'] ?? '',
            ];
            $fields = $user_data['data']['extended_fields']['field'];
            if (is_array($fields)) {
                foreach ($fields as  $value) {
                    $data[$value['name']] = $value['value'];
                }
            }
            return view('Profile.edit', ['data' => $data]);
        } catch (\Exception $e) {
            return $this->throwLogin($e);
        }
    }

    public function update(Request $request)
    {
        $this->service->updateValidate();
        $update = $this->service->updateUserDetails($request);
        if ($update['success'] == false) {
            if ($update['data'] == "Session has been Expired!") {
                return to_route('login_page')->with('false', $update['data']);
            }
            return redirect()->back()->with('false', 'Something went wrong!');
        }
        return to_route('edit');
    }

    public function delete()
    {
        $service = new AuthService();
        $headers = [
                'cap_authorization' => request()->header('cap-authorization'),
                'cap_brand' => request()->header('cap-brand'),
                'cap_device_id' => request()->header('cap-device-id'),
                'cap_mobile' => request()->header('cap-mobile'),
        ];
        
        $delete = $service->delete($headers);
        if($delete['status']['success'] == false)
        {
            return redirect()->back()->with('false','Something went wrong! please contact our support.');
        }
        return to_route('login_page')->with('true','Account closed successfully!');
    }

    public function termsConditions()
    {
        return view('Profile.terms&conditions.index');
    }

    public function throwLogin($errmsg = '')
    {

        return to_route('login_page')->with('false', ($errmsg != '' ? $errmsg : 'Something went wrong!'));
    }

    public function changePassword()
    {
        return view('Profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $service = new AuthService();
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|different:old_password',
            'password_confirmation' => 'required'
        ]);
        $data = [
            "identifierType" => "MOBILE",
            "identifierValue" => request()->header('cap-mobile'),
            "deviceId" => request()->header('cap-device-id'),
            "brand" => request()->header('cap-brand'),
            "password" => $request->password,
            "confirmPassword" => $request->password_confirmation,
        ];
        $generatedToken = $this->generate_token->tokenGenerate($data);
        if(isset($generatedToken['user']['sessionId'])){
            $data['sessionId']       = $generatedToken['user']['sessionId'];
            $data['token']           = request()->header('cap-authorization');
            $data['password']        = $request->old_password;
            $data['newPassword']        = $request->password;
            $data['confirmPassword'] = $request->password;
        }
        $updated = $service->updatePassword($data);
        if($updated['status']['success'] == true)
        {
            return redirect()->back()->with('true','Password updated!');
        }
        if($updated['status']['success'] == false)
        {
            return redirect()->back()->with('false',$updated['status']['message']);
        }
        return redirect()->back()->with('false','Password updation failed!');
    }
}
