<?php
namespace framework\components;

class SensitiveWordFilter
{
    const redisKey = 'sensitive_word_dict';
	private $dict;
	private $default_max_distance = 3;

    /**
     * SensitiveWordFilter constructor.
     * @param $words
     * @param $type  0：直接传入词典   1：传入敏感词数组
     */
	public function __construct($words,$type)
	{
	    if($type == 0){
            $this->dict = $words;
        }else{
            $redis = ToolsAbstract::getRedis();
            foreach ($words as $word) {
                $word = trim($word);
                $this->dictAdd($word);
            }
            $redis->set(self::redisKey, serialize($this->dict));
        }
    }

	private function dictAdd($word){
		$uWord = $this->unicodeSplit($word);
		$pdict = &$this->dict;

		$count = count($uWord);
		for ($i = 0; $i < $count; $i++) {
			if (!isset($pdict[$uWord[$i]])) {
				$pdict[$uWord[$i]] = array();
			}
			$pdict = &$pdict[$uWord[$i]];
		}

		$pdict['end'] = true;
	}

	public function filter($str, $maxDistance = null)
	{
		if(!$maxDistance){
			$maxDistance = $this->default_max_distance;
		}
		if ($maxDistance < 1) {
			$maxDistance = 1;
		}

		$uStr = $this->unicodeSplit($str);

		$count = count($uStr);

		for ($i = 0; $i < $count; $i++) {
			if (isset($this->dict[$uStr[$i]])) {
				$pdict = &$this->dict[$uStr[$i]];

				$matchIndexes = array();

				for ($j = $i + 1, $d = 0; $d < $maxDistance && $j < $count; $j++, $d++) {
					if (isset($pdict[$uStr[$j]])) {
						$matchIndexes[] = $j;
						$pdict = &$pdict[$uStr[$j]];
						$d = -1;
					}
				}

				// if成立，则匹配
				if (isset($pdict['end'])) {
					$uStr[$i] = '*';
					foreach ($matchIndexes as $k) {
						if ($k - $i == 1) {
							$i = $k;
						}
						$uStr[$k] = '*';
					}
				}
				//echo json_encode($matchIndexes);
			}
		}



		return implode($uStr);
	}

	public function hasSensitiveWords($str, $maxDistance = null)
	{
		if(!$maxDistance){
			$maxDistance = $this->default_max_distance;
		}
		if ($maxDistance < 1) {
			$maxDistance = 1;
		}

		$uStr = $this->unicodeSplit($str);

		$count = count($uStr);

		for ($i = 0; $i < $count; $i++) {
			if (isset($this->dict[$uStr[$i]])) {
				$pdict = &$this->dict[$uStr[$i]];

				$matchIndexes = array();

				for ($j = $i + 1, $d = 0; $d < $maxDistance && $j < $count; $j++, $d++) {
					if (isset($pdict[$uStr[$j]])) {
						$matchIndexes[] = $j;
						$pdict = &$pdict[$uStr[$j]];
						$d = -1;
					}
				}

				// if成立，则匹配
				if (isset($pdict['end'])) {
					return true;
				}
				//echo json_encode($matchIndexes);
			}
		}

		return false;

	}

	public function unicodeSplit($str)
	{
		$str = strtolower($str);
		$ret = array();
		$len = strlen($str);
		for ($i = 0; $i < $len; $i++) {
			$c = ord($str[$i]);

			if ($c & 0x80) {
				if (($c & 0xf8) == 0xf0 && $len - $i >= 4) {
					if ((ord($str[$i + 1]) & 0xc0) == 0x80 && (ord($str[$i + 2]) & 0xc0) == 0x80 && (ord($str[$i + 3]) & 0xc0) == 0x80) {
						$uc = substr($str, $i, 4);
						$ret[] = $uc;
						$i += 3;
					}
				} else if (($c & 0xf0) == 0xe0 && $len - $i >= 3) {
					if ((ord($str[$i + 1]) & 0xc0) == 0x80 && (ord($str[$i + 2]) & 0xc0) == 0x80) {
						$uc = substr($str, $i, 3);
						$ret[] = $uc;
						$i += 2;
					}
				} else if (($c & 0xe0) == 0xc0 && $len - $i >= 2) {
					if ((ord($str[$i + 1]) & 0xc0) == 0x80) {
						$uc = substr($str, $i, 2);
						$ret[] = $uc;
						$i += 1;
					}
				}
			} else {
				$ret[] = $str[$i];
			}
		}

		return $ret;
	}

}