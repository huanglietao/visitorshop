<?php
/**
 * 画框上传
 *
 * 画框是两个元素，必须有规定的命名格式才有效
 * 例如： xxxx.jpg  xxxx_后缀.jpg
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2018/7/24
 */

namespace App\Services\Template\Material\Upload;


use App\Model\MaterialAttachment;
use App\Model\TemplatesAttachment;
use App\Models\SaasMaterialAttachment;
use App\Models\SaasTemplatesAttachment;
use Intervention\Image\ImageManager;

class Frame extends Base
{
    public function run(){
        $params = $this->config;
        $file = $this->file;
        //如果是压缩包
//        $path_info = pathinfo($file['name']);
//        if($path_info['extension'] == 'zip' || $path_info['extension'] == 'rar'){
//            $this->zipUp($params,$file);
//            exit;
//        }
        $orig_name = $file['name'];
        $hq_hz = $params['frame_hz'];
        if(empty($params['page_type'])) {
            $mpath = 'material_atta_path';
        } else {
            $mpath = 'temp_attach_path';
        }

        //如果是蒙版图片
        if(strstr($orig_name,$hq_hz)){
            //是否已经记录了外框数据
            $border_name = str_replace($hq_hz,'',$orig_name);
            if(empty($params['page_type'])) {
                $model = new SaasMaterialAttachment();
                $material_info = $model->where(['material_atta_orig_name'=>$border_name,'material_atta_uniqid'=>$params['uniqid']])->get()->toArray();
            }
            else{
                $model = new SaasTemplatesAttachment();
                $material_info = $model->where(['temp_attach_orig_name'=>$border_name,'temp_attach_material_type'=>$params['type'],'temp_attach_uniqid'=>$params['uniqid']])->get()->toArray();
            }

            //$material_info = $material_info[0];
            //已经存在了边框
            if(!empty($material_info)){
                $path = $this->getDir().'/'.$this->main.'/'.$this->big.'/'.str_replace('.',$hq_hz.'.',$material_info[0][$mpath]);
                // var_dump($path);exit;
                if (!(move_uploaded_file($file["tmp_name"], $path) )) { //移动失败
                    $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
                    return false;
                } else { //移动成功
                    $paths['big'] = $path;
                    $paths['mid'] = $this->getDir().'/'.$this->main.'/'.$this->mid.'/'.str_replace('.',$hq_hz.'.',$material_info[0][$mpath]);
                    $paths['sml'] = $this->getDir().'/'.$this->main.'/'.$this->sml.'/'.str_replace('.',$hq_hz.'.',$material_info[0][$mpath]);

                    $this->resize($paths);
                    $this->stateInfo = $this->errorMap[0];
                    return ['success'=>true,'data'=>['oriName'=>$file['name'],'id'=>0, 'url' =>str_replace('.',$hq_hz.'.',$material_info[0][$mpath]) ]];
                }
            }else{
                $this->beforeRun($hq_hz);
                $info = $this->getFileInfo();
                $paths['big'] = $this->getDir().'/'.$this->main.'/'.$this->big.'/'.$info['full_name'];
                $paths['mid'] = $this->getDir().'/'.$this->main.'/'.$this->mid.'/'.$info['full_name'];
                $paths['sml'] = $this->getDir().'/'.$this->main.'/'.$this->sml.'/'.$info['full_name'];
                $info['file_name'] = str_replace('.',$hq_hz.'.',$info['file_name']);
                $this->resize($paths);
                if($this->stateInfo != 0){
                    return ['success'=>false,'data'=>$this->stateInfo];
                }else{
                    return ['success'=>true,'data'=>$info];
                }
            }

        }else{

            //var_dump('我不是蒙版');
            //对应的蒙版名字
            $mask_name =str_replace('.',$hq_hz.'.',$orig_name);
            if(empty($params['page_type'])) {
                $model = new SaasMaterialAttachment();
                $material_info = $model->where(['material_atta_orig_name'=>$mask_name,'material_atta_uniqid'=>$params['uniqid']])->get()->toArray();
            }
            else{
                $model = new SaasTemplatesAttachment();
                $material_info = $model->where(['temp_attach_orig_name'=>$mask_name,'temp_attach_material_type'=>$params['type'],'temp_attach_uniqid'=>$params['uniqid']])->get()->toArray();
            }
            if(!empty($material_info)){
                $path = $this->getDir().'/'.$this->main.'/'.$this->big.'/'.str_replace($hq_hz,'',$material_info[0][$mpath]);
                // var_dump($path);exit;
                if (!(move_uploaded_file($file["tmp_name"], $path) )) { //移动失败
                    $this->stateInfo = $this->getStateInfo("ERROR_FILE_MOVE");
                    return false;
                } else { //移动成功
                    $paths['big'] = $path;
                    $paths['mid'] = $this->getDir().'/'.$this->main.'/'.$this->mid.'/'.str_replace($hq_hz,'',$material_info[0][$mpath]);
                    $paths['sml'] = $this->getDir().'/'.$this->main.'/'.$this->sml.'/'.str_replace($hq_hz,'',$material_info[0][$mpath]);
                    $this->resize($paths);
                    $this->stateInfo = $this->errorMap[0];
                    return ['success'=>true,'data'=>['oriName'=>$file['name'],'id'=>0, 'url' =>str_replace($hq_hz,'',$material_info[0][$mpath])]];
                }

            }else{
                $this->beforeRun();
                $info = $this->getFileInfo();
                $paths['big'] = $this->getDir().'/'.$this->main.'/'.$this->big.'/'.$info['full_name'];
                $paths['mid'] = $this->getDir().'/'.$this->main.'/'.$this->mid.'/'.$info['full_name'];
                $paths['sml'] = $this->getDir().'/'.$this->main.'/'.$this->sml.'/'.$info['full_name'];
                $this->resize($paths);
                if($this->stateInfo != 0){
                    return ['success'=>false,'data'=>$this->stateInfo];
                }else{
                    return ['success'=>true,'data'=>$info];
                }
            }
        }

        //图片改尺寸


    }

    private function resize($paths){
        $manager = new ImageManager(array('driver' => 'gd'));
        $sys_config = $this->sysConfig;

        $params = [];
        //需要切的参数
        if(isset($sys_config['crop']['mid']))
            $params[$this->mid] = $sys_config['crop']['mid'];
        if(isset($sys_config['crop']['sml']))
            $params[$this->sml] = $sys_config['crop']['sml'];

        if(!empty($params)){
            foreach($params as $k=>$v){
                $img = $manager->make($paths[$this->big]);
                $img->resize($v,null,function ($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($paths[$k]);
            }
        }

    }

    private function zipUp($params , $file)
    {
        $this->beforeRun();
        $info = $this->getFileInfo();

        $path = pathinfo($this->getDir() . '/' . $info['full_name']);
        $dir_name = $path['dirname'];
        $zippy = Zippy::load();
        $archive = $zippy->open($this->getDir() . '/' . $info['full_name']);
        $archive->extract($dir_name);
    }
}