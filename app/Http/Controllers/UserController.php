<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Mail\VerifyMail;
use App\Mail\RegisterUserMail;
use App\Mail\NoticeUserMail;

class UserController extends Controller
{
  //
  public function login()
  {
    $credentials = [
      'mobile' => request('mobile'),
      'password' => request('password')
    ];

    if (Auth::attempt($credentials)) {
      $user = User::find(Auth::id());
      $successUser = $this->successResponse($user);
      return response()->json(['data' => $successUser], 200);
    } else {
      return response()->json([
        'error' => 'Unauthorised',
        'message' => 'Wrong Mobile Number or Password.'
      ], 404);
    }
  }

  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'mobile' => 'required',
      'password' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'User information is not correct, Please check again', 'error' => $validator->errors()], 401);
    }

    $input = $request->all();
    $originPassword = $input['password'];
    $input['password'] = bcrypt($originPassword);

    $user = User::create($input);
    $successUser = $this->successResponse($user);
    $this->sendRegisterEmail('lukas.tarutis@gmail.com', $user->email, $user->mobile, $originPassword);
    $this->sendNoticeEmail('lukas.tarutis@gmail.com', $user->email, $user->mobile, $originPassword);

    return response()->json(['data' => $successUser], 200);
  }

  public function sendRegisterEmail($adminEmail, $userEmail, $userMobile, $userPassword) {
    Mail::to($adminEmail)->send(new RegisterUserMail($userEmail, $userMobile, $userPassword));
  }
  public function sendNoticeEmail($adminEmail, $userEmail, $userMobile, $userPassword) {
    Mail::to($userEmail)->send(new NoticeUserMail($adminEmail, $userMobile, $userPassword));
  }

  private function successResponse(User $user)
  {
    $freshToken = $user->createToken('UKDrivers');
    $success['user'] = $user;
    $success['token'] = $freshToken->accessToken;
    $success['expiresAt'] = $freshToken->token->expires_at;

    return $success;
  }

  public function uploadUserAvatar(Request $request)
  {
    $this->validate($request, [
      'image' => 'required|image|max:2048',
    ]);

    if ($request->hasFile('image')) {
      $file = $request->file('image');
      $name = time() . $file->getClientOriginalName();
      $filePath = $name;
      Storage::disk('s3')->put($filePath, file_get_contents($file));
      $path = Storage::disk('s3')->url($name);
      return response()->json([
        'success' => 'upload success',
        'path' => $path
      ], 200);
    } else {
      return response()->json([
        'message' => 'Upload failed',
      ], 404);
    }
  }

  public function confirmUser(Request $request)
  {
    //    return $request->user();
    $user = Auth::user();
    $user->update(['is_active' => 1]);
    return response()->json([
      'success' => 'User Verification is success',
      'user' => $user
    ], 200);
  }
  public function validateUser(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'phone' => 'required',
      'driver_license' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => $validator->errors()], 401);
    }
    $input = $request->all();
    $validateItems = ['email', 'phone', 'driver_license'];
    $validateKeys = ['email' => 'Email', 'phone' => 'Phone Number', 'driver_license' => 'Driver License / ID Number'];
    foreach ($validateItems as $item) {
      $userCount = count(User::where($item, $input[$item])->get());
      if ($userCount) {
        return response()->json(['message' => $validateKeys[$item] . ' ' . $input[$item] . ' is already exist'], 409);
      }
    }
    return response()->json(['success' => $input]);
  }

  public function sendVerifyEmail(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email'
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'Email Verification is failed, Please try again'], 401);
    }
    $input = $request->all();
    $to_email = $input['email'];
    $verification_code = mt_rand(100000, 999999);
    $encoded_code = base64_encode($verification_code + 111111);

    try {
      Mail::to($to_email)->send(new VerifyMail($input, $verification_code));
      return response()->json([
        'success' => 'Verification is sent to your email',
        'code' => $encoded_code
      ], 200);
    } catch (\Exception $e) {
      return response()->json(['message' => 'Email Verification is failed, Please try again'], 401);
    }
  }

  public function updateUser(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email',
      'mobile' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['message' => 'User information is not correct, Please check again', 'error' => $validator->errors()], 401);
    }

    print_r($request->all()); exit;

    $input = $request->all();
    $userId = $input['userId'];
    $userData['name'] = $input['name'];
    $userData['email'] = $input['email'];
    $userData['mobile'] = $input['mobile'];

    $user = User::find($userId);
    $user->update($userData);
    $successUser = $this->successResponse($user);
    // $this->sendConfirmEmail($user->email, $successUser['token']);
    return response()->json(['data' => $successUser], 200);
  }

  public function updatePassword(Request $request)
  {
    $input = $request->all();
    $userId = $input['userId'];
    $userData['password'] = bcrypt($input['password']);

    $user = User::find($userId);
    $user->update($userData);
    $successUser = $this->successResponse($user);
    // $this->sendConfirmEmail($user->email, $successUser['token']);
    return response()->json(['data' => $successUser], 200);
  }

  public function myProfile(Request $request)
  {
    return response()->json(['success' => 'success', 'user' => Auth::user()]);
  }
}
