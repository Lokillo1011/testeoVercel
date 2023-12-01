<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUsuarioRequest;
use App\Http\Requests\RegistroUsuarioRequest;
use App\Mail\RegistroUsuario;
use App\Mail\VerificarUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Metodo para registrase como usuario
     * @param RegistroUsuarioRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegistroUsuarioRequest $request)
    {
        DB::beginTransaction();
        try {
            $tokenMail = bin2hex(random_bytes(64));
            $usuario = User::create([
                'name'                     => $request->nombre,
                'email'                    => $request->correo,
                'password'                 => bcrypt($request->contrasenia),
                'email_verification_token' => $tokenMail,
            ]);
            Mail::to($usuario->email)->send(new RegistroUsuario($usuario));
//            $token = $usuario->createToken('auth_token', ['expires_in' => 60])->plainTextToken;
            DB::commit();
            return response()->json([
                'message' => 'Usuario registrado correctamente, se ha enviado un correo de verificación para activar su cuenta',
                'data'    => $usuario,
                'status'  => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }
    }

    public function login(Request $request)
    {
        $credenciales = [
            'email'    => $request->correo,
            'password' => $request->contrasenia,
        ];
        if (!auth()->attempt($credenciales)) {
            return redirect()->route('login')->with('errors', 'Credenciales incorrectas');
        }

        if (!$user = User::where('email', $request->correo)->where('activo', 1)->first()) {
            return redirect()->route('login')->with('errors  ', 'Usuario no activo');
        }
        Session::put('usuario', $user);
        $user->tokens()->delete();
        $token = auth()->user()->createToken('auth_token', ['expires_in' => 3600], Carbon::now('America/Mexico_City')->addHour())->plainTextToken;
        Session::put('token', $token);
        Session::save();
//        return response()->json([
//            'access_token' => $token,
//            'message'      => 'Usuario logueado correctamente',
//            'status'       => 'success',
//            'usuario'      => $user,
//        ], 200);
        //pasa las variables de sesion a la vista de sanctum
        return redirect()->route('inicio');
    }

    public function verifyUser(Request $request)
    {
        $user = User::where('email_verification_token', $request->token)->first();
        if (!$user) throw new HttpException(404, 'Usuario no encontrado');
        DB::beginTransaction();
        try {
            $verificarEmail = $user->update([
                'email_verified_at'        => Carbon::now(),
                'email_verification_token' => null,
                'activo'                   => 1,
            ]);
            if (!$verificarEmail) throw new HttpException(500, 'Error al verificar el correo');
            Mail::to($user->email)->send(new VerificarUsuario($user));
            DB::commit();
            return response()->json(['message' => 'Usuario verificado correctamente',
                                     'status'  => 'success'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }

    }

    public function logout(Request $request)
    {
        $user = User::where('id', session()->get('usuario')->id)->first();
        $user->tokens()->delete();
        //vaciar la sesion
        session()->flush();
        return redirect()->route('login');
    }

    public function inicio()
    {
        //pasa las variables de sesion a la vista de sanctum
        return view('inicio', ['usuario' => session()->get('usuario'), 'token' => session()->get('token')]);
    }
    public function papelera()
    {
        //pasa las variables de sesion a la vista de sanctum
        return view('invoices.papelera', ['usuario' => session()->get('usuario'), 'token' => session()->get('token')]);
    }
}

