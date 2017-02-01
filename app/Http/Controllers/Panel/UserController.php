<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use Auth;
use DB;
use Log;
use App\Models\User;
use App\Jobs\SubmitVerificationEmail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;
use Resizer;

class UserController extends Controller
{
    /**
     * Refresh Captcha instance & get captcha image url
     * @param string $config
     * @return \Illuminate\Http\JsonResponse
     */
    public function touchCaptcha($config = 'flat')
    {
        return response()->json([captcha_src($config)]);
    }

    /**
     * Signup
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postSignup(Request $request)
    {
        $user = new User();

        // Validate input
        $this->validate($request, $user->getValidationRules($user), $user->getValidationMessages());

        // Create a new user
        $user = $user->create($request->all());

        $this->submitVerificationEmail($user);

        // Force login for the created user
        Auth::login($user, true);

        // Show confirmation form
        return response()->json(['location'=>'/verify']);
    }

    /**
     * Login
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request)
    {
        $user = new User();

        $this->validate($request, $user->getLoginValidationRules(), $user->getLoginValidationMessages());

        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password], true)) {

            return response()->json(['location'=>'/admin']);
        }else{
            return response()->json(['message'=>'Ошибочный пароль или пользователь не зарегистрирован']);
        }
    }

    public function postVerify(Request $request)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        if($request->has('email')){
            $rules = ['email'=>'email'];

            if($user->email != $request->input('email')) {
                $rules['email'] .= '|unique:' . $user->getTable() . ',email';
            }

            $this->validate($request, $rules, $user->getValidationMessages());

            if($user->email != $request->input('email')) $user->update($request->all());
        }


        try {
            $this->submitVerificationEmail($user);

            return response()->json(['location'=>'/verify/sent']);
        }catch (\Exception $e){
            Log::error($e->getMessage(), [__FILE__, __LINE__]);
            return response()->json(['message'=>'Ошибка при отправке e-mail']);
        }
    }

    public function logout()
    {
        if(Auth::check()){

            Auth::logout();

            return redirect(url()->route('admin::login'));
        }
    }

    public function profile()
    {
        $data = [];

        /**
         * @var $user User
         */
        $user = Auth::user();

        $data['owner'] = $user;
        $data['itemsTotal'] = $user->getItemsTotal();
        $data['reviewsTotal'] = $user->getShopReviewsTotal();
        $data['avatarEditable'] = true;

        return view('user.profile', $data);
    }

    public function modifyProfile(Request $request)
    {
        /**
         * Get current user
         * @var $user User
         */
        $user = Auth::user();

        // Validate input
        $this->validate($request, $user->getValidationRules($user), $user->getValidationMessages());

        // Set unverified if different email
        $user->verified = ($user->email != $request->input('email')) ? 0 : 1;

        // Submit confirmation email in a production environment if different email
        if(!$user->verified) {
            $this->submitVerificationEmail($user);
        }

        // Password change
        if($request->has('_password')){
            $user->password = bcrypt($request->input('_password'));
        }

        // Resize & store avatar image
        $avatar = $request->file('_avatar');

        if(!empty($avatar) && !$avatar->getError()){
            Resizer::load($request->file('_avatar')->getPathname())
                ->resize(130, 130, true)
                ->save(public_path().'/images/avatars/'.$user->id.'.jpg');

            $user->touch();
        }else throw new \ErrorException;

        $user->update($request->all());

        return response()->json(['location'=>'/profile']);
    }

    public function deleteAvatar(Request $request){
        /**
         * @var $user User
         */
        $user = Auth::user();

        $path = public_path().'/images/avatars/'.$user->id.'.jpg';

        if(file_exists($path)) unlink($path);

        return response()->json(['location'=>'/profile']);
    }

    public function deleteProfile(Request $request)
    {
        /**
         * @var $user User
         */
        $user = Auth::user();

        $user->destroy($user->id);

        $this->logout();

        return response()->json(['location'=>'/']);
    }

    public function doVerify(Request $request, $token)
    {
        if(empty($token)) throw new NotFoundHttpException;

        /**
         * @var $user User
         */
        $user = User::where(DB::raw('CONCAT(token, id)'), $token)->first();

        if(empty($user)) throw new NotFoundHttpException;

        $user->verified = 1;
        $user->token = NULL;

        $user->update();

        return redirect('/verified');
    }

    /**
     * Submit verification email in a production environment
     * @param User $user
     */
    public function submitVerificationEmail(User $user)
    {
        if(!env('APP_DEBUG')) {
            $user->setAttribute('token', Str::random(24));

            if(!$user->update()) {
                throw new \PDOException;
            }

            $this->dispatch(new SubmitVerificationEmail($user));
        }
    }
}
