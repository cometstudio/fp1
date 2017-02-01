<?php

namespace App\Providers\Instagram;

use Curl;
use Illuminate\Http\Request;

class Instagram {

    public function auth(Request $request)
    {
        $settings = view()->shared('settings');

        if($code = $request->get('code')){

            $data = [
                'client_id'=>env('INSTAGRAM_CLIENT_ID'),
                'client_secret'=>env('INSTAGRAM_CLIENT_SECRET'),
                'grant_type'=>'authorization_code',
                'redirect_uri'=>'http://fitnespraktika.ru/instagram/auth',
                'code'=>$code
            ];

            $response = Curl::to('https://api.instagram.com/oauth/access_token')
                ->withData($data)
                ->post();

            if(!empty($response)) {
                $json = json_decode($response);

                if(!empty($json->access_token)) {
                    $settings->instagram_access_token = $json->access_token;
                    $settings->update();
                }
            }
        }
    }

    private function getAccessToken()
    {
        try{
            $settings = view()->shared('settings');

            if(empty($settings->instagram_access_token)) throw new \Exception;

            return $settings->instagram_access_token;
        }catch (\Exception $e){
            return '';
        }
    }

    public function like($mediaId = null)
    {
        try{
            if(empty($mediaId)) throw new \Exception;

            $data=[
                'access_token'=>$this->getAccessToken()
            ];

            $response = Curl::to('https://api.instagram.com/v1/media/'.$mediaId.'/likes')
                ->withData($data)
                ->post();

            if(empty($response)) throw new \Exception;

            $json = json_decode($response);

            if(empty($json->meta->code)) throw new \Exception;

            if(intval($json->meta->code) != 200) throw new \Exception;

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function follow($userId = 0)
    {
        try{
            if(empty($userId)) throw new \Exception;

            $data=[
                'access_token'=>$this->getAccessToken(),
                'action'=>'follow'
            ];

            $response = Curl::to('https://api.instagram.com/v1/users/'.$userId.'/relationship')
                ->withData($data)
                ->post();

            if(empty($response)) throw new \Exception;

            $json = json_decode($response);

            if(empty($json->meta->code)) throw new \Exception;

            if(intval($json->meta->code) != 200) throw new \Exception;

            return true;
        }catch (\Exception $e){
            return false;
        }
    }

    public function _touchAPI($method = '', $data = [])
    {
        try{
            if(empty($method)) throw new \Exception;

            $data = array_merge([], $data);

            Curl::to('https://api.telegram.org/bot' . env('TELEGRAM_TOKEN') . '/'.$method)
                ->withData($data)
                ->post();

            return true;
        }catch (\Exception $e){
            return false;
        }
    }
}
