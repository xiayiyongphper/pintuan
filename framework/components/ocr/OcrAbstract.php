<?php

namespace framework\components\ocr;
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-3-29
 * Time: 下午2:18
 */
abstract class OcrAbstract
{
    /**
     * 识别图片中的文字
     * @return mixed
     */
    protected abstract function recognize();

    /**
     * 返回目标内容，与图片识别之后的内容的匹配度，要求目标中所有的文字都在识别的结果中出现
     * @param $text
     * @param $matchPercent
     * @return boolean
     */
    public abstract function match($text, $matchPercent);
}