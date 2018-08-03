<?php
namespace common\tools;

use PHPExcel;
use PHPExcel\IOFactory;
/**
 * 公用的excel操作类库
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/13
 * Time: 11:17
 */
class Excel
{
    /**
     * 读取excel表格的数据
     * @param $fileName
     * @param bool  $filterHeader是否过滤第一行
     * @return array
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Reader_Exception
     */
    public static function readExcelSheet($fileName, $filterHeader=false)
    {
        $PHPReader = \PHPExcel_IOFactory::load($fileName);
        $data = $PHPReader->getActiveSheet()
                          ->toArray(null,true, true, false);

        //过滤标题
        if ($data && true === $filterHeader) {
                unset($data[1]);
        }
        return $data;
    }

    /**
     * 数据导出
     * @param array $title   标题行名称
     * @param array $data   导出数据
     * @param string $fileName 文件名
     * @return string   返回文件全路径
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * demo：exportExcel(array('姓名','年龄'), array(array('a',21),array('b',23)), '档案', './', true);
     */
    public static function exportExcel($title=[], $data=[], $fileName='')
    {
        $obj = new PHPExcel();
        //横向单元格标识
        $cellName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];

        $obj->getActiveSheet(0)->setTitle('sheet名称');//设置sheet名称
        $_row = 1;   //设置纵向单元格标识
        if($title){
            $_cnt = count($title);
            //$obj->getActiveSheet(0)->mergeCells('A'.$_row.':'.$cellName[$_cnt-1].$_row);   //合并单元格
            //$obj->setActiveSheetIndex(0)->setCellValue('A'.$_row, '数据导出：'.date('Y-m-d H:i:s'));  //设置合并后的单元格内容
            $i = 0;
            foreach($title AS $v){ //设置列标题
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);
                $i++;
            }
            $_row++;
        }
        //填写数据
        if($data){
            $i = 0;
            foreach($data AS $_v){
                $j = 0;
                foreach($_v AS $_cell){
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);
                    $j++;
                }
                $i++;
            }
        }
        //文件名处理
        if(!$fileName){
            $fileName = uniqid(time(),true);
        }
        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        header('pragma:public');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");
        $objWrite->save('php://output');
        exit;
    }

    /**
     * 生成excel文件
     * @param array $title   标题行名称
     * @param array $data   导出数据
     * @param string $fileName 文件名
     * @return string   返回文件全路径
     * @throws PHPExcel_Exception
     * @throws PHPExcel_Reader_Exception
     * demo：exportExcel(array('姓名','年龄'), array(array('a',21),array('b',23)), '档案', './', true);
     */
    public static function exportExcel2($title=[], $data=[], $fileName='')
    {
        $obj = new PHPExcel();
        //横向单元格标识
        $cellName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];

        $obj->getActiveSheet(0)->setTitle($fileName);//设置sheet名称
        $_row = 1;   //设置纵向单元格标识
        $_cnt = 0;
        if($title){
            $_cnt = count($title);
            $i = 0;
            foreach($title AS $v){ //设置列标题
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i].$_row, $v);
                $i++;
            }
            $_row++;
        }

        $obj->getActiveSheet(0)->getColumnDimension('A')->setWidth(50);//设置列宽度
        $obj->getActiveSheet(0)->getColumnDimension('B')->setWidth(50);//设置列宽度
        $obj->getActiveSheet(0)->getColumnDimension('C')->setWidth(50);//设置列宽度

        $last = strtoupper($cellName[$_cnt-1]);

        //填写数据
        if($data){
            $i = 0;
            foreach($data AS $_v){
                $j = 0;

                $index = ($i+$_row);
                if (empty($_v[0])) {
                    $obj->getActiveSheet(0)->getStyle( "A{$index}:{$last}{$index}")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                    $obj->getActiveSheet(0)->getStyle( "A{$index}:{$last}{$index}")->getFill()->getStartColor()->setARGB('FFFF00');
                } else if ($_v[0] == '总计') {
                    $obj->getActiveSheet(0)->getStyle( "A{$index}:{$last}{$index}")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                    $obj->getActiveSheet(0)->getStyle( "A{$index}:{$last}{$index}")->getFill()->getStartColor()->setARGB('99CCFF');
                }

                foreach($_v AS $_cell){
                    $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i+$_row), $_cell);
                    $obj->getActiveSheet(0)->getStyle($cellName[$j] . ($i+$_row))->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                    $j++;
                }
                $i++;
            }
        }

        if(!$fileName){
            $fileName = uniqid(time(),true);
        }

        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
        $pathFile = '/home/www/publish/pintuan-cms/backend/web/orderexcel/' . $fileName .'.xlsx';
        $objWrite->save($pathFile);
    }
}