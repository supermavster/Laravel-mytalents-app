<?php
namespace App\Http\Controllers\API;

use DB;
use Hash;
use Mail;
use JWTAuth;
use App\Role;
use App\User;
use Validator;
use App\TalentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    /**
     * API Register
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
    $credentials = $request->only(
            'talent_type_id',
            'name',
            'surname',
            'birthday',
            'gender',
            'phone',
            'email',
            'profile_photo',
            'password'
            );

        $rules = [
            'talent_type_id'=>  'numeric',
            'name'          =>  'string|required',
            'surname'       =>  'string|required',
            'birthday'      =>  'required|date',
            'gender'        =>  'string|required|max:9',
            'phone'         =>  'numeric|required',
            'email'         =>  'required|max:255|email|unique:users',
            'profile_photo' =>  'required|mimes:jpg,png,gif,jpeg,mp4,mkv,flv',
            'password'      =>  'required|min:8'
        ];
        $validator = Validator::make($credentials, $rules);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'error' => $validator->messages()]);
        }

        $talentType =   $request->talent_type_id;
        $name       =   $request->name;
        $surname    =   $request->surname;
        $birthday   =   $request->birthday;
        $gender     =   $request->gender;
        $phone      =   $request->phone;
        $email      =   $request->email;
        $password   =   $request->password;

        if ($request->profile_photo) {
            $image_name = time().$request->profile_photo->getClientOriginalName();
            $profile_photo = $image_name;
        }

        $user = User::create([
                'talent_type_id'=>$talentType,
                'name' => $name,
                'surname' => $surname,
                'birthday'=> $birthday,
                'gender'=>$gender,
                'phone'=>$phone,
                'email' => $email,
                'administrator'=>User::USER_ARTIST,
                'profile_photo'=>$profile_photo,
                'status' =>User::USER_ACTIVE,
                'password' => Hash::make($password)
            ]);

        Role::create([
            'user_id'   =>  $user->id,
            'role'      =>  $user->administrator
        ]);

        foreach ($request->talent_id as $talent) {
            $talent = DB::table('talent_user')->insert([
                    'user_id'=>$user->id,
                    'talent_id'=>$talent
                ]);
        }

        Storage::disk('images')->put($user->name.'_'.$user->id.'/'.$user->profile_photo, File::get($request->profile_photo));

        $verification_code = str_random(30);

        DB::table('user_verifications')->insert(['user_id' => $user->id, 'token' => $verification_code]);
        $subject = "Please verify your email address.";
        Mail::send('email.verify', ['name' => $name, 'verification_code' => $verification_code],
            function($mail) use ($email, $name, $subject){
                $mail->from(getenv('FROM_EMAIL_ADDRESS'), "From User/Company Name Goes Here");
                $mail->to($email, $name);
                $mail->subject($subject);
            });
        return response()->json(['success'=> true, 'message'=> 'Thanks for signing up! Please check your email to complete your registration.']);
    }

     /**
     * API Verify User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
     public function verifyUser($verification_code)
    {
        $check = DB::table('user_verifications')->where('token', $verification_code)->first();

        if (!is_null($check)) {
            $user = User::find($check->user_id);
            if ($user->is_verified == 1) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Cuenta verificada satisfactoriamente..',
                ]);
            }
            $user->update(['is_verified' => 1]);

            DB::table('user_verifications')->where('token', $verification_code)->delete();

            return view("verificar", [
                'ok' => true,
                'message' => 'Has verificado correctamente tu dirección de correo electrónico.',
            ]);
        }
        return view("verificar", ['ok' => false, 'message' => "El código de verificación no es válido."]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];
        $validator = Validator::make($credentials, $rules);
        
        if ($validator->fails()) {
            return response()->json(['ok' => false, 'error' => $validator->messages()], 401);
        }

        $credentials['is_verified'] = 1;

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(
                    [
                    'success' => false,
                    'error' => 'No podemos encontrar una cuenta con estas credenciales. Asegúrese de haber ingresado la información correcta y de haber verificado su dirección de correo electrónico.'],
                    404
                );
            }
        } catch (JWTException $e) {
            return response()->json(
                [
                'success' => false,
                'error' => 'Error al iniciar sesión, por favor intente de nuevo.'],
                500
            );
        }

        $user = \Auth::user();

        return response()->json([
                'success' => true,
                'data' => ['token' => $token],
                'user' => ['data' => $user]
            ], 200);
    }
    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     */
    public function logout(Request $request)
    {
        $this->validate($request, ['token' => 'required']);

        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['ok' => true, 'message' => "Has cerrado sesión correctamente."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['ok' => false, 'error' => 'Error al cerrar sesión, por favor intente de nuevo.'], 500);
        }
    }

    public function recover(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            $error_message = "Tu dirección de correo electrónico no fue encontrada.";
            return response()->json(['ok' => false, 'error' => ['email' => $error_message]], 401);
        }
        try {
            Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject('Reestablecer Contraseña');
            });
        } catch (\Exception $e) {
            //Return with error
            $error_message = $e->getMessage();
            return response()->json(['ok' => false, 'error' => $error_message], 401);
        }
        return response()->json([
            'ok' => true, 'data' => ['message' => '¡Se ha enviado un correo electronico de reinicio de contraseña! Por favor revise su correo electrónico.'],
        ]);
    }

}