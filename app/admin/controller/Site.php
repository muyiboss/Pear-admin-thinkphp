<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\common\model\Site as SiteModel;
use think\facade\View;
class Site extends Base
{
    public function index()
    {
        if ($this->request->isAjax()) {
            foreach ($this->request->post() as $k => $v) {
                SiteModel::where('name', $k)->update(['value'=> $v]);
            }
            $this->returnApi('保存成功', 1);
        }
        return View::fetch('', [
            'data' => SiteModel::column('value', 'name')
        ]);
    }
}
