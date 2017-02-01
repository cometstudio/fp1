<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Jobs\SubmitVerificationEmail;
use App\Jobs\SubmitForgotEmail;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    protected $css = 'user';

    public function login()
    {
        $title = 'Войти на сайт';

        return view(
            'users.login', [
                'css'=>$this->css,
                'title'=>$title,
            ]
        );
    }

    /**
     * Login & Signup router
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request)
    {
        $user = new User();

        // If signup
        if($request->input('_new')){
            return $this->postSignup($request);
        }else{
            $this->validate($request, $user->getLoginValidationRules(), $user->getLoginValidationMessages());

            $email = $request->input('email');
            $password = $request->input('password');

            return $this->forceLogin($email, $password);
        }
    }

    public function forgot()
    {
        $title = 'Напомнить пароль';

        return view(
            'users.forgot', [
                'css'=>$this->css,
                'title'=>$title,
            ]
        );
    }

    /**
     * Submit a password to the user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
      @throws \Exception
     */
    public function postForgot(Request $request)
    {
        $abstractUser = new User();

        // Validate input
        $this->validate($request, $abstractUser->getForgotValidationRules(), $abstractUser->getForgotValidationMessages());

        try{
            if(Auth::check()) throw new \Exception('Вы авторизованы');

            $email = $request->get('email');

            $user = User::where('email', '=', $email)->first();

            if(empty($user)) throw new \Exception('Пользователь с указанными данными не зарегистрирован');

            $password = Str::random(4);

            $user->setAttribute('password', $user::encryptPassword($password));

            $this->submitForgotEmail($user, $password);

            return response()->json(['location'=>url()->route('login', [], false)]);
        }catch (\Exception $e){
            return response()->json(['message'=>$e->getMessage()]);
        }
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
        $this->validate($request, $user->getSignupValidationRules(), $user->getSignupValidationMessages());

        $email = $request->input('email');
        $password = $request->input('password');

        // Create a new user
        $user = $user->create($request->all());

        $this->submitVerificationEmail($user);

        // Force login for the created user
        return $this->forceLogin($email, $password);
    }

    /**
     * Refresh Captcha instance & get captcha image url
     * @param string $config
     * @return \Illuminate\Http\JsonResponse
     */
    public function touchCaptcha($config = 'default')
    {
        return response()->json([captcha_src($config)]);
    }

    private function forceLogin($email, $password, $id = 0)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password], true)) {
            return response()->json(['location'=>url()->route('index', [], false)]);
        }else{
            return response()->json(['message'=>'Ошибочный пароль или пользователь не зарегистрирован']);
        }
    }

    public function _postVerify(Request $request)
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
            return response()->json(['message'=>'Ошибка при отправке e-mail']);
        }
    }

    public function doVerify(Request $request, $token)
    {
        if(empty($token)) throw new NotFoundHttpException;

        /**
         * @var $user User
         */
        $user = User::where('token', $token)->firstOrFail();

        $user->verified = 1;
        $user->token = NULL;

        $user->update();

        return redirect(url()->route('index', [], false));
    }

    /**
     * Submit forgot email in a production environment
     * @param User $user
     */
    public function submitForgotEmail(User $user, $password)
    {
        if(env('APP_ENV') == 'production') {

            if(!$user->update()) {
                throw new \PDOException;
            }

            $this->dispatch(new SubmitForgotEmail($user, $password));
        }
    }

    /**
     * Submit verification email in a production environment
     * @param User $user
     */
    public function submitVerificationEmail(User $user)
    {
        if(env('APP_ENV') == 'production') {
            $user->setAttribute('token', Str::random(24));

            if(!$user->update()) {
                throw new \PDOException;
            }

            $this->dispatch(new SubmitVerificationEmail($user));
        }
    }

    public function logout()
    {
        if(Auth::check()){

            Auth::logout();

            return redirect(url()->route('index', [], false));
        }

        return false;
    }
}
