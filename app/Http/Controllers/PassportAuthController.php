<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;
class PassportAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'type' => 'required|integer',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type?$request->type:0,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = Auth::user()->token();
        $user->revoke();
        $tokens =  $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked'=> true]);

        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);
        return response()->json(['message' => 'Logout'], 200);
    }
}
