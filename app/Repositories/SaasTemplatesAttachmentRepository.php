<?php
namespace App\Repositories;

use App\Models\SaasMaterialAttachment;
use App\Models\SaasTemplatesAttachment;

/**
 * 模板/素材相关附件处理
 *
 * 功能详细说明
 * @author: yanxs <541139655@qq.com>
 * @version: 1.0
 * @date: 2020/4/28
 */
class SaasTemplatesAttachmentRepository extends BaseRepository
{
    protected $materAttaModel;
    public function __construct(SaasTemplatesAttachment $model, SaasMaterialAttachment $materAttaModel)
    {
        $this->model = $model;
        $this->materAttaModel = $materAttaModel;
    }

    /**
     * 插入通用素材的附件
     * @param $data
     */
    public function insertMaterialAtta($data)
    {
        return $this->materAttaModel->create($data);
    }
}