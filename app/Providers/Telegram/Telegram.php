<?php

/*
Example usage:
    \Resizer::load($_SERVER['DOCUMENT_ROOT'].'/test.jpg')
                ->resize(100, 100)
                ->save($_SERVER['DOCUMENT_ROOT'].'/images/output.jpg');
*/

namespace App\Providers\Telegram;

use Curl;

class Telegram {

    public function sendChatAction($chatId = 0, $action = 'typing')
    {
        $this->touchAPI('sendChatAction', [
           'chat_id'=>$chatId,
           'action'=>$action,
        ]);
    }

    public function touchAPI($method = '', $data = [])
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
