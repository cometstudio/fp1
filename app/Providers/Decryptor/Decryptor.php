<?php

namespace App\Providers\Decryptor;

use Curl;

class Decryptor
{
    private $config = [];
    private $url;
    private $key;
    private $id;

    private $binaryMartix;
    public $assocNumber = array(
        'ec8b2fb58169e4fb6b8399ea29b29d6f'=>'0',
        '6041d62fff1508477a7de09ce8da0f7d'=>'0',
        '7f41aa20c424abc8ca92851c6233bcc0'=>'0',
        '3b7733d334a70557f189050643629634'=>'1',
        '19b3cfb9ee4af968210336ba2c8e4e72'=>'1',
        '76fd3a58ae13f2fd59618ec86ce2d6d3'=>'1',
        'cda47f4a34753429eb9bb80a1d80b17a'=>'2',
        'a320ccf2635e97f5a048f4852f0fa431'=>'2',
        'd6e5dad03497d302954ab6ff5cfd7882'=>'2',
        'b774440319ee08928ba46c5cfc9628af'=>'3',
        'c637997d804badd18130e9190658f077'=>'3',
        '69014699307db93379e633100b22b017'=>'3',
        '552b702a9eb04636d87b4be98e7af2bc'=>'4',
        '3e0c84780d1f1cb21d2f41002fbc97f9'=>'4',
        'b326fe8a51c44a6bdf4e4547adbbfe39'=>'4',
        '596d6855da310a2390903f4bc5fed428'=>'5',
        '5f20f00980324f1179117ac054661562'=>'5',
        '910164ace5793011eed6de91185e9480'=>'5',
        '31eff0f86e7c053cccb69643965791fa'=>'6',
        'b2a06835bc878fdbdad48345e9761859'=>'6',
        '364f90ed02195cb7daac174406281a8f'=>'6',
        'e0fb024be71b4410e1fb4d857ae843b7'=>'7',
        '72751379c0abb29e74b549aaffb4bef8'=>'7',
        'c40dbf5dc206f9f6a91a49b4af51c0f2'=>'7',
        '89929a554a4b9b081b1c545eea998033'=>'8',
        '44aac2cd3114a60c9805313a70f4fdb5'=>'8',
        '7a989cf39b0cb62bb5747d630f4c796c'=>'8',
        '50b0a4cf2f6d8a5cadd001cd9b95c307'=>'8',
        'd0ca537ef27b71735a6a66300aa6c48a'=>'9',
        'bc8fd9074cbade09802195ce970a2d02'=>'9',
        '46e11f3272b352eb9c7e7b11ab178e01'=>'9',
        '48633e0f6705f235a1499e664dd81445'=>'9',
        '224677fd08e0e492a4dea57b05abda67'=>'-',
        'c5d0d982cad4b2d2433b722236e3669e'=>'-',
        'fa3aa22882029544e5b5369a656f35eb'=>'-',
        '7da526ad154d984ce3f4795cc200832e'=>'+',
        '5a33d219d114c40991c8f18dfa01125b'=>'+',
        '6a540c26ef73553a18e5d6b4490cc891'=>'+4',
        '320763cd46a1688ccf8f39029e4476cd'=>'-4',
        '886cfe3b2e060aead92d4547c5f17d22'=>'-4',
        'fcab2e2d5902275f8a59ebb8f502ab34'=>'04',
        '015e2638fec6d602afa242cfcb4cf147'=>'04',
        '767a43246f4f6c5f6a3ae04368df654e'=>'24',
        '0b388a1560708491956190a09cbbb277'=>'34',
        'e42639f04172aed34a684d83b20c593f'=>'44',
        '72ce253180e32f695209bf68ff8e7a8e'=>'54',
        '34365c9dbd7d3970bb8061996c4cbaf6'=>'54',
        '3d0d7709514058fd7db2e61db0dd69b7'=>'64',
        'a2c0c8af7f5b30d9bed078149c342f70'=>'74',
        '819c7a6693851563daccf85e90127f4c'=>'84',
        'b4f8e2a929e19905ea48ad5a7902b56d'=>'94',
        '5d9a416223f7493ed308cce7fd43b5d6'=>'40',
        '0ea53e9c76c27f90ec9551c3d64bb156'=>'42',
        'dbb946c5bd324760eb0fb314252f1e55'=>'43',
        '7b6cc9a3dfa904fefb3eee03f4a58835'=>'44',
        '3c73b7e64f148f7885be6d02cdb70003'=>'45',
        '4a53990949ee7c7c1d26b504797a4a90'=>'46',
        '8ba023b16d6169914d3d4da956b11aea'=>'47',
        '90f57b1a8e947e86d8e98d29bb13ccb7'=>'48',
        'd8594b81a9ef58f975acf30c850e60b5'=>'49',
        '460235457cbd28cf2eb100872e9171fd'=>'64',
        '91674ee42dc747d6b6bf3a51349c3626'=>'74',
        '64bb6e611379efb2f7fbb6547d8f5fe6'=>'94',
        '2b929c3e75af442742a42d57d6919d8c'=>'0-',
        'e24982b86ea57bc397143eb0f53deb64'=>'0-',
        '3199d5e962c170155459801a012763f2'=>'2-',
        'aecd8416cb3ef478ba9f269f57e42023'=>'2-',
        '6a154b91c985a271eb25ac3df8615f3a'=>'3-',
        '1f91e6f775a3cf93ac06b52dd88408c5'=>'3-',
        '60cd0c9d2f842a2a5e49336ba536f769'=>'4-',
        'd950d7fff60f354b5b5b9a99897564ce'=>'4-',
        'e7e5ec787d2717d3105e5e9299796245'=>'4-',
        'da67a06e914e9a52a117503e1a3fd5b6'=>'5-',
        'ec80d51d8f87d1ea1d52090288dc7ab2'=>'5-',
        '7f87f13e683b37b34497d34d46ae5f83'=>'6-',
        '2635b3462075e29ec390acc8370c501e'=>'6-',
        '6744a63f05a5de71ed252665f3579a94'=>'7-',
        'eb3a025c7dbfde6b16c75a158d2dec68'=>'7-',
        '8bbe8b9f62442dd86af632f123f402b1'=>'8-',
        '94cbafbde868aef5fed6bd2639d42faa'=>'8-',
        '2026d70628705ea6138b45e90e83bb64'=>'9-',
        '31a2be5737e578658c7d76ab99fd692d'=>'9-',
        'b862af2b975d0a7b417e46b08936cdaa'=>'0-4',
        '09856fcea6b676ebbac7e4ac0232dc01'=>'2-4',
        '3c8d85c5aefc8a9e8aba90ea4a8d3fae'=>'2-4',
        'b6067f61befc8ebdbebabf1dad080700'=>'3-4',
        '94acb512cde2bb88cf4e2d1fabe202dc'=>'4-4',
        'c85bc75c2ffdd1ff9b9a6f7108907623'=>'4-4',
        'd4f1999f6c94d103b8ba970755b6914b'=>'5-4',
        '16dae7b0100ca6880e4350db391770bc'=>'5-4',
        '41daeb885efbb70b91acfffd5e16ce97'=>'6-4',
        'c3fe5ba4ca2a57715422dc34e2dfcc4d'=>'7-4',
        'f7972e5761fd35973d543f46367a2c62'=>'8-4',
        'de8477bfca137d013a0ec05a02f93e41'=>'9-4',
        '3a1b576e712ad293e34920da62e194b3'=>'9-4',
        '532f38d88d1504da7adf9d6404bd3bdb'=>'044',
        'f5ea47b8a897e330627eba767adaef82'=>'244',
        '4b59c392c2b10badb23a17b3e0b360c0'=>'344',
        'f8771eac4cebe1b75a75a30d132e483e'=>'344',
        'b63ea7ef147af204cb51168aaef49036'=>'444',
        '316b1f3fe66ff3f0274c0a573b2f8833'=>'544',
        'e52904309acb1aa06396086bf980bf33'=>'644',
        '1cf1fe1a0685e9d37cce63ddb53fc991'=>'744',
        'bc56f6617d7cf094e2da8cba0c88b8e1'=>'844',
        '0e8ba26dc513300dc35f9d36f186f083'=>'944',
        '7fcfea69b3328075298e996369283209'=>'-44',
        'c61ef8e1cef2093af6addc814724421a'=>'+44'
    );
    private $resolve;

    public function __construct(){
        $this->config = config('avito', []);

        return $this;
    }

    public function set($url, $id, $key)
    {
        $this->url = $url;
        $this->id = $id;
        $this->key = $key;

        return $this;
    }

    public function getPhoneImageUrl()
    {
        $key = preg_replace('|[^0-9a-f]+|isU', ',', $this->key);

        $s = strlen(str_replace(',', '', $key));

        $key = explode(',', $key);

        if(!($this->id%2)) $key = array_reverse($key);

        $key = implode('', $key);

        $r='';

        for($k=0; $k<$s; $k++){
            if(!($k%3)){
                $r.=substr($key, $k,1);
            }
        }

        $pkey = $r;

        $phoneImageUrl = $this->config['protocol'].':'.$this->config['base_url'].'/items/phone/'.$this->id.'?pkey='.$pkey;

        return $phoneImageUrl;
    }

    public function getPhoneImage()
    {
        $phoneImageUrl = $this->getPhoneImageUrl();

        $json = Curl::to($phoneImageUrl)
            ->withHeader('Referer: '.$this->url)
            ->get();

        $data  = json_decode($json);

        $imageData = explode(',', $data->image64);

        $imageEncoded = end($imageData);

        $image = base64_decode($imageEncoded);;

        file_put_contents(storage_path('phone.png'), $image);

//        $image = file_get_contents(storage_path('phone.png'));

        return $image;
    }

    public function decode($url, $id, $key)
    {
        $image = $this->set($url, $id, $key)->getPhoneImage();

        $srcIm = @imagecreatefromstring($image);

        $srcH = imagesy($srcIm);

        $this->binaryMartix = $this->imageToMatrix($srcIm);

        $explode = $this->getcolum($this->binaryMartix);

        $respone = [];

        $respone['error'] = 0;

        foreach ($explode as $index=>$number) {

            $number_md5 = md5($number['string']);

            // Есть совпадение
            if(isset($this->assocNumber[$number_md5])) {
                $this->resolve .= $this->assocNumber[$number_md5];
            } else {
                // Создание изображений
                imagejpeg($srcIm, storage_path('phone1.png'));
                $src = imagecreatefromjpeg(storage_path('phone1.png'));

                $start_pos = $number['start_pos'];
                $end_pos = $number['end_pos'];

                $dest = imagecreatetruecolor($end_pos-$start_pos, $srcH);

                // Копирование
                imagecopy($dest, $src, 0, 0, $start_pos, 0, $end_pos, $srcH);
                // Вывод и освобождение памяти
                imagejpeg($dest, storage_path('phone2.jpg'));

                imagedestroy($dest);
                imagedestroy($src);

                $number_cont = file_get_contents(storage_path('phone2.jpg'));

                $respone['md5'][strlen($this->resolve)]['md5'] = $number_md5;
                $respone['md5'][strlen($this->resolve)]['img'] = $number_cont;
                $respone['md5'][strlen($this->resolve)]['start'] = $start_pos;
                $respone['md5'][strlen($this->resolve)]['end'] = $end_pos;
                $respone['error'] = 1;
                $respone['full_img'] = $image;
                $this->resolve .= '_';
            }
        }

        $respone['phone'] = str_replace('-', '', $this->resolve);

        dd($respone);

        return $respone;
    }

    private function imageToMatrix($image)
    {
        $height = imagesx($image);
        $width = imagesy($image);

        $matrix = '';
        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $rgb = imagecolorat($image, $i, $j);
                list($r, $g, $b) = array_values(imagecolorsforindex($image, $rgb));

                if($r>250 && $g>250 && $b>250 ) {
                    $matrix .= 0;
                } else {
                    $matrix .= 1;
                }
            }
            $matrix .= PHP_EOL;
        }
        return $matrix;
    }

    private function getcolum($binaryMartix)
    {
        $explode = array();
        $count = 0;
        $binaryMartix = explode(PHP_EOL, $binaryMartix);

        foreach($binaryMartix as $index=>$val) {
            $sum = 0;
            for($i=0; $i<strlen($val); $i++) {
                $sum += $val[$i];
            }
            if($sum != 0) {
                if(!isset($explode[$count])) {
                    $explode[$count]['string'] = $val.PHP_EOL;
                    $explode[$count]['start_pos'] = $index;
                } else {
                    $explode[$count]['string'] .= $val.PHP_EOL;
                }
            } else {
                if(isset($explode[$count]['string'])) {
                    $explode[$count]['end_pos'] = $index;
                }
                $count++;
            }
        }

        return $explode;
    }
}