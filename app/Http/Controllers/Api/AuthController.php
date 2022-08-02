<?php

namespace App\Http\Controllers\Api;

use App\Mail\SendMail;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:customers,email',
            'phone'             => 'required|numeric|unique:customers,phone',
            'password'          => 'required|min:6',
        ];

        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $customer = Customer::create([
            'first_name'        => $request->first_name,
            'last_name'         => $request->last_name,
            'email'             => $request->email,
            'phone'             => $request->phone,
            'password'          => Hash::make($request->password),
        ]);

        $token = $customer->createToken('web1')->plainTextToken;
        $customer['token'] = $token;
        return $this->mainResponse(true, 'You are loggedin.', $customer);;
    }

    public function mainResponse($status, $message, $data, $error = [], $code = 200)
    {
        $errors = [];
        foreach($error as $key => $value)
        {
            $errors[] = ['filed_name' => $key, 'message' => $value];
        }
        return response()->json(compact('code', 'status', 'message', 'data', 'errors'), $code);
    }

    public function login(Request $request)
    {
        $rules = [
            'email'     => 'required|exists:customers,email',
            'password'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $customer = Customer::whereEmail($request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return $this->mainResponse(false, 'Password incorrect.', null, ['Password' => 'Password incorrect.'], 422);
        } else {
            $token = $customer->createToken('loginToken')->plainTextToken;
            $customer['token'] = $token;
            return $this->mainResponse(true, 'logged in successfully.', $customer);
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
        return $this->mainResponse(false, 'You are logged out.', null);
    }

    public function changePassword(Request $request)
    {
        $customer = Customer::query()->find(auth()->user()->id);
        $rules = [
            'current_password'              => 'required',
            'password'                      => 'required|min:6|confirmed',
            'password_confirmation'         => 'required',
        ];

        if (!Hash::check($request->current_password, $customer->password)) {
            return $this->mainResponse(false, 'Your current password is incorrect.', null, ['Password' => 'Your current password is incorrect.'], 422);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }


        $customer->update([
            'password' => Hash::make($request->password),
        ]);

        return $this->mainResponse(true, 'Password updated successfully.', null);
    }

    public function forgotPassword(Request $request)
    {
        $rules = [
            'email' => 'required|exists:customers,email',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        VerificationCode::whereEmail($request->email)->delete();
        $this->sendCreateCode($request->email);

        return $this->mainResponse(true, 'Check your email', null);
    }

    public function sendCreateCode($email)
    {
        $code = '';

        for($i = 0; $i < 4; $i++)
        {
            $code .= rand(0, 9);
        }

        Mail::to($email)->send(new SendMail($code));

        return VerificationCode::create([
            'email' => $email,
            'code'  => Hash::make($code),
            'step'  => 1,
        ]);
    }


    public function checkCode(Request $request)
    {
        $rules = [
            'email' => 'required|exists:verification_codes,email',
            'code'  => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        $record = VerificationCode::whereEmail($request->email)->first();

        if (!Hash::check($request->code, $record->code))
        {
            return $this->mainResponse(false, 'Code is incorrect.', null, ['Code' => 'Code is incorrect.'], 422);
        }else{
            VerificationCode::whereEmail($request->email)->update([
                'step' => 2,
            ]);

            return $this->mainResponse(true, 'Code is correct.', null);
        }
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'email'                     => 'required|exists:verification_codes,email',
            'password'                  => 'required|min:6|confirmed',
            'password_confirmation'     => 'required',
        ];

        $verify = VerificationCode::whereEmail($request->email)->first();

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            return $this->mainResponse(false, $validator->errors()->first(), null, $validator->errors()->messages(), 422);
        }

        //check if the customer verify the code
        if ($verify->step != 2)
        {
            return $this->mainResponse(false, 'You must verify your code, please check your email.', null, ['step' => 'You must verify your code, please check your email.'], 422);
        }

        $customer = Customer::whereEmail($request->email)->first();

        $customer->update([
            'password' => Hash::make($request->password),
        ]);

        VerificationCode::whereEmail($request->email)->delete();

        return $this->mainResponse(true, 'Password reset successfully.', null);
    }
}
