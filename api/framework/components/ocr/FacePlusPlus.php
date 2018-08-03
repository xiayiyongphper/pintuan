<?php

namespace framework\components\ocr;

use yii\base\ErrorException;

/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-3-29
 * Time: 下午2:18
 */
class FacePlusPlus extends OcrAbstract
{
    protected $url = 'https://api-cn.faceplusplus.com/imagepp/beta/recognizetext';
    protected $key = "-sdJ5lEDV7wlCEO7iMHqLH7Hh__GCwyL";
    protected $secret = "1osnsVXSw3BVpQVQaQAGZxAb9W_FVwIc";
    protected $image;
    protected $encode = 'UTF-8';
    protected $recognizeText;

    /**
     * FacePlusPlus constructor.
     * @param string $image 文件url
     */
    public function __construct($image)
    {
        $this->image = $image;
    }

    protected function recognize()
    {
        if (!$this->image) {
            throw new \Exception('文件地址不存在');
        }
        $formData = [
            'api_key' => $this->key,
            'api_secret' => $this->secret,
            'image_url' => $this->image,
        ];
        $curl = curl_init($this->url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $formData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($curl);
        if (curl_errno($curl) !== 0) {
            throw new ErrorException(curl_error($curl), curl_errno($curl));
        }
        $result = json_decode($result, true);
        $objects = $result['result'];
        $values = [];
        foreach ($objects as $object) {
            $values[] = $object['value'];
        }
        $text = implode('', $values);
        //去掉数字、字母、特殊字符
        $text = preg_replace('/[\d\w\s]/', '', $text);
        return $text;
    }

    public function getRecognizeText()
    {
        if (!$this->recognizeText) {
            $this->recognizeText = $this->recognize();
        }
        return $this->recognizeText;
    }

    /**
     * @param $text
     * @param $matchPercent
     * @return boolean
     */
    public function match($text, $matchPercent)
    {
        $recognizeText = $this->getRecognizeText();
        $length = mb_strlen($text, $this->encode);
        $score = 0;
        $prevIndex = -1;
        for ($i = 0; $i < $length; $i++) {
            $word = mb_substr($text, $i, 1, $this->encode);
            $index = mb_strpos($recognizeText, $word, null, $this->encode);
            //echo $word . ':' . $index . PHP_EOL;
            if ($index === false) {
                //扣分,暂不处理，goto fail
                $score -= 1;
            }
            if ($index > $prevIndex) {
                $prevIndex = $index;
                $score += 1;
            } else {
                //目标文本，在识别的结果中的顺序不是递增，扣分
                //goto fail;
                $score -= 1;
            }
        }
        $score = ($score / $length) * 100;
        if ($score >= $matchPercent) {
            return true;
        }
        return false;
    }
}
