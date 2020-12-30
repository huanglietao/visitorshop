<?php
namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;

/**
 * 项目说明
 * 详细说明
 * @author:
 * @version: 1.0
 * @date:
 */
class UploadController
{


    /*
        * 流程
        * 1.移动临时文件到指定目录
        * 2.判断是否是最后一块，并进行合并
        * 3.删除临时文件及目录
        * 4.返回相关信息
        */
    private $file_path='';//上传目录
    private $temp_path='';//php文件临时目录
    private $blob_num;//第几片
    private $total_num;//总片数
    private $file_name;//文件名
    private $temp_name;//php上传的临时文件目录


    /**
     *upload constructor.
     * @access  public
     * @param   string $filePath
     * @param   string|integer $blobNum
     * @param   string|integer $totalNum
     * @param   string $fileName
     * @param   string $tempName
     *
     */
    public function __construct($filePath,$blobNum,$totalNum,$fileName,$tempName){
        $this->file_path=$filePath;
        $this->blob_num=$blobNum;
        $this->total_num=$totalNum;
        $this->file_name=$fileName;
        $this->temp_name=$tempName;
        $this->temp_path=config('agent.works_file').config('agent.works_pdf_file_temp');
        $this->moveFile();
        $this->mergeFile();

    }
    //移动临时文件
    private function moveFile(){
        $this->touchDir();
        //将php上传的临时文件移动到临时目录
        $filename=$this->temp_path.$this->file_name.'_'.$this->blob_num;
        move_uploaded_file($this->temp_name,$filename);
    }
    //合并文件
    private function mergeFile(){
        //当前分片序号（从0开始）等于总分片数-1
        if($this->blob_num==($this->total_num-1)){
            $blob='';
            //使用fopen
            //使用file_get(put)_contents
            //先判断文件是否已经存在
            if(file_exists($this->file_path.iconv('UTF-8','GB2312',$this->file_name))){
                @unlink($this->file_path.iconv('UTF-8','GB2312',$this->file_name));
            }
            for($i=0;$i<$this->total_num;$i++){
                $blob=file_get_contents($this->temp_path.$this->file_name.'_'.$i);
                $last_path=$this->file_path.$this->file_name;
                iconv('UTF-8','GB2312',$this->file_path.$this->file_name);
                file_put_contents($last_path,$blob,FILE_APPEND);
            }
            $this->deleteTempFile();
        }
    }
    //删除上传的临时文件
    private function deleteTempFile(){
        for($i=0;$i<$this->total_num;$i++){
            @unlink($this->temp_path.$this->file_name.'_'.$i);
        }
    }
    //创建文件架
    private function touchDir(){
        //上传目录
        if(!file_exists($this->file_path)){
            $oldmask=umask(0);
            @mkdir($this->file_path,0777,true);
            umask($oldmask);
        }
        //临时文件上传目录
        if(!file_exists($this->temp_path)){
            @mkdir($this->temp_path,0777,true);
        }
        return;
    }
    //API返回数据GB
    public function apiReturn(){
        if($this->blob_num==($this->total_num-1)){
            //修改文件权限
            $oldmask=umask(0);
            $res=chmod($this->file_path.$this->file_name,0777);
            umask($oldmask);
            $res1=$this->file_path.$this->file_name;
            $res2=file_exists($res1);
            if($res2){
                $data['code']=2;
                $data['msg']='success';
                $data['file_path']=$this->file_path.$this->file_name;
            }
        }else{
            if(file_exists($this->temp_path.$this->file_name.'_'.$this->blob_num)){
                $data['code']=1;
                $data['msg']='error';
                $data['file_path']='';
            }
        }
        return $data;
    }




}