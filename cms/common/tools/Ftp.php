<?php
namespace common\tools;

class Ftp
{
    protected static $_url = 'http://cdn.lelai.com/cdn_file_receiver.php';//48.98152589798:324k,3.2532389163971:3.9k
    protected static $imgs_url = 'http://cdn.lelai.com/cdn_imgs_receiver.php';// 多图片上传
//    protected $_url = 'http://assets.lelai.com/cdn_file_receiver.php';//206.61732316017:324k,202.07829880714:3.9k
    /**
     * 32MB
     * @var int
     */
    protected static $_uploadMaxFileSize = 33554432;

    /**
     * 允许上传的文件类型
     * @var array
     */
    protected static $_allowedTypes = array(
        'image/gif',
        'image/jpg',
        'image/jpeg',
        'image/png',
        'application/msword',
        'text/plain',
        'application/vnd.ms-excel',
        'application/vnd.ms-office',
        'application/zip',
    );

    /**
     * 允许上传的扩展名
     * @var array
     */
    protected static $_allowedExtensions = array(
        'gif',
        'bmp',
        'jpg',
        'png',
        'jpeg',
        'csv',
        'xls',
        'xlsx',
        'doc',
        'txt',
        'html'
    );

    /**
     * @param $path
     * @param $filename
     * @param $entity
     * @param bool $returnSize
     * @return mixed
     * @throws \Exception
     */
    public static function upload($path, $filename, $entity = 'merchant', $returnSize = false)
    {
        /*$fInfo = finfo_open(FILEINFO_MIME_TYPE);
        $type = finfo_file($fInfo, $path);
        finfo_close($fInfo);*/
        $parts = explode('.', $filename);
        $extension = strtolower(end($parts));

        if (filesize($path) > self::$_uploadMaxFileSize) {
            throw new \Exception('File size is out of range.');
        }

        /*if (!in_array($type, $this->_allowedTypes)) {
            throw new Exception('File type not allowed to upload.');
        }*/

        if (!in_array($extension, self::$_allowedExtensions)) {
            throw new \Exception('File extension not allowed to upload.');
        }

        $data = array(
//            'file' => '@' . realpath($path) . ";filename=" . $filename,
            //'file' => '@' . realpath($path) ,
            'file' => new \CURLFile(realpath($path), null, strtolower($filename)),
            'entity' => $entity,
            'return_size' => $returnSize,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 一次性上次多个图片
     * @param $urlArr 多个远程图片url
     * @return mixed
     * @throws \Exception
     */
    public static function uploadMulFiles($urlArr,$product="")
    {
        if(empty($urlArr) || !is_array($urlArr)){
            Tools::log(__CLASS__.'--'.__METHOD__.'--'.'data is error.','ftp_error.log');
//            throw new \Exception('data is error.');
            return false;
        }
        $data = [];
        foreach ($urlArr as $key => $val){
            if (strlen($val) > self::$_uploadMaxFileSize) {
                Tools::log(__CLASS__.'--'.__METHOD__.'--'.'File size is out of range.','ftp_error.log');
//                throw new \Exception('File size is out of range.');
                continue;
            }
            $parts = explode('.', $val);
            $extension = strtolower(end($parts));
            if (!in_array($extension, self::$_allowedExtensions)) {
                Tools::log(__CLASS__.'--'.__METHOD__.'--'.'File extension not allowed to upload.'."-- extension:".$extension,'ftp_error.log');
//                throw new \Exception('File extension not allowed to upload.');
                continue;
            }
            $data[] = $val;
        }
        $url = self::$imgs_url;
        if(!empty($product) && $product == 'product'){ // 文件要放到product 中切图
            $url = $url."?type={$product}";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpcode != 200){
            Tools::log(__CLASS__.'--'.__METHOD__.'--'.'httpcode is error: '.$httpcode,'ftp_error.log');
//            throw new \Exception('httpcode is error: '.$httpcode);
            return false;
        }
        curl_close($ch);
        return $result;
    }

}