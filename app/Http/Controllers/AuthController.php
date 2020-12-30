<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;


/**
 * 权限验证类
 * @author: cjx
 * @version: 1.0
 * @date: 2020/01/09
 */

class AuthController
{
    protected $adminId;              //管理员id
    protected $rules = [];
    protected $arr;
    protected $system;              //系统标识
    protected $pidname = '_auth_rule_pid';

    protected $config = [
        'auth_group'            =>      '',            // 用户组数据表名
        'auth_rule'             =>      '',            // 权限规则表
        'auth_user'             =>      '',            // 用户信息表
    ];

    public function __construct($auth_group, $auth_rule, $auth_user, $system)
    {

        $this->adminId = session('admin')['admin_id'];
        $this->config['auth_group'] = $auth_group;
        $this->config['auth_rule'] = $auth_rule;
        $this->config['auth_user'] = $auth_user;
        $this->system = $system;
    }

    /**
     * 获取左侧菜单栏
     * @return string
     */
    public function getSidebar($fixedPage = 'dashboard',$where = [])
    {

        // 读取管理员当前拥有的权限节点
        $userRule = $this->getRuleList();

        $ruleList = DB::table($this->config['auth_rule'])
                    ->where($this->system.'_auth_rule_status',1)
                    ->where($this->system.'_auth_rule_ismenu',1)
                    ->where($where)
                    ->whereNull('deleted_at')
                    ->orderBy($this->system.'_auth_rule_weigh', 'desc')
                    ->get();

        $select_id = 0;
        $ruleList = json_decode($ruleList,true);
        foreach ($ruleList as $k => &$v)
        {
            if (!in_array($v[$this->system.'_auth_rule_name'], $userRule))
            {
                unset($ruleList[$k]);
                continue;
            }
            $select_id = $v[$this->system.'_auth_rule_name'] == $fixedPage ? $v[$this->system.'_auth_rule_id'] : $select_id;
            $v[$this->system.'_url'] = '/'.$v[$this->system.'_auth_rule_name'];
        }

        $this->arr = $ruleList;
        $str = $this->getTreeMenu(0,'<li class="@class"><a href="javascript:void(0);" data-id="@id" data-url="@url" class="@childClass"><i class="nav-icon @'.$this->system.'_auth_rule_icon"></i> <p>@'.$this->system.'_auth_rule_title@caret</p></a> @childlist</li>', $select_id, '', 'ul', 'class="nav nav-treeview menu-lv-1"');
        return $str;
    }

    /**
     * 菜单数据
     * @param int $myid
     * @param string $itemtpl
     * @param mixed $selectedids
     * @param mixed $disabledids
     * @param string $wraptag
     * @param string $wrapattr
     * @param int $deeplevel
     * @return string
     */
    public function getTreeMenu($myid, $itemtpl, $selectedids = '', $disabledids = '', $wraptag = 'ul', $wrapattr = '', $deeplevel = 0)
    {
        $str = '';
        $childs = $this->getChild($myid);
        if ($childs)
        {

            foreach ($childs as $value)
            {
//                dd($value);
                $id = $value[$this->system.'_auth_rule_id'];
                unset($value['child']);
                $selected = in_array($id, (is_array($selectedids) ? $selectedids : explode(',', $selectedids))) ? 'selected' : '';
                $disabled = in_array($id, (is_array($disabledids) ? $disabledids : explode(',', $disabledids))) ? 'disabled' : '';
                $value = array_merge($value, array('selected' => $selected, 'disabled' => $disabled));
                $value = array_combine(array_map(function($k) {
                    return '@' . $k;
                }, array_keys($value)), $value);

                $bakvalue = array_intersect_key($value, array_flip(['@url', '@caret', '@class']));
                $value = array_diff_key($value, $bakvalue);
                $nstr = strtr($itemtpl, $value);
                $value = array_merge($value, $bakvalue);
                $childdata = $this->getTreeMenu($id, $itemtpl, $selectedids, $disabledids, $wraptag, $wrapattr, $deeplevel + 1);
                $childlist = $childdata ? "<{$wraptag} {$wrapattr}>" . $childdata . "</{$wraptag}>" : "";
                $childlist = strtr($childlist, array('@class' => $childdata ? 'last' : ''));
                $value = array(
                    '@childlist' => $childlist,
                    '@url'       => $childdata || !isset($value['@'.$this->system.'_url']) ? " " : $value['@'.$this->system.'_url'],
                    '@addtabs'   => $childdata || !isset($value['@'.$this->system.'url']) ? "" : (stripos($value['@'.$this->system.'url'], "?") !== false ? "&" : "?") . "",
                    '@caret'     => ($childdata && (!isset($value['@'.$this->system.'badge']) || !$value['@badge']) ? '<i class="fa fa-angle-left right fa-lg"></i>' : ''),
                    '@badge'     => isset($value['@badge']) ? $value['@badge'] : '',
                    '@class'     => ($selected ? 'nav-item has-treeview menu-open' : 'nav-item has-treeview') . ($disabled ? ' disabled' : '') . ($childdata ? '' : ''),
                    '@childClass'=> $deeplevel == 1 ? 'nav-link menu-link' : ($selected ? 'nav-link menu-link active menu-default' : 'nav-link')
                );
                $str .= strtr($nstr, $value);
            }
        }
        return $str;
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    public function getChild($myid)
    {
        $newarr = [];
        foreach ($this->arr as $value)
        {
            if (!isset($value[$this->system.'_auth_rule_id']))
                continue;
            if ($value[$this->system.$this->pidname] == $myid)
                $newarr[$value[$this->system.'_auth_rule_id']] = $value;
        }
        return $newarr;
    }

    /**
     * 获得权限规则列表
     * @return array
     */
    protected function getRuleList()
    {
        static $_rulelist = []; //保存用户验证通过的权限列表

        // 读取用户规则节点
        $ids = $this->getRuleIds($this->adminId);
        if (empty($ids))
        {
            $_rulelist[$this->adminId] = [];
            return [];
        }

        $query = DB::table($this->config['auth_rule'])->where($this->system.'_auth_rule_status',1)->whereNull('deleted_at');

        // 筛选条件
        if (!in_array('*', $ids))
        {
            $query->whereIn($this->system.'_auth_rule_id',$ids);
        }

        //读取用户组所有权限规则
        $this->rules = $query->select($this->system.'_auth_rule_id',$this->system.'_auth_rule_pid',$this->system.'_auth_rule_condition',$this->system.'_auth_rule_icon',$this->system.'_auth_rule_name',$this->system.'_auth_rule_title',$this->system.'_auth_rule_ismenu')->get();

        //循环规则，判断结果。
        $rulelist = [];
        if (in_array('*', $ids))
        {
            $rulelist[] = "*";
        }

        $this->rules = json_decode($this->rules,true);

        foreach ($this->rules as $rule)
        {
            //超级管理员无需验证condition
            if (!empty($rule[$this->system.'_auth_rule_condition']) && !in_array('*', $ids))
            {
                //根据condition进行验证
                $command = preg_replace('/\{(\w*?)\}/', '$user[\'\\1\']', $rule[$this->system.'_auth_rule_condition']);
                @(eval('$condition=(' . $command . ');'));
                if ($condition)
                {
                    $rulelist[$rule[$this->system.'_auth_rule_id']] = strtolower($rule[$this->system.'_auth_rule_name']);
                }
            }
            else
            {
                //只要存在就记录
                $rulelist[$rule[$this->system.'_auth_rule_id']] = strtolower($rule[$this->system.'_auth_rule_name']);
            }
        }
        $_rulelist[$this->adminId] = $rulelist;

        return array_unique($rulelist);
    }

    /**
     * 根据用户id获取菜单节点id,返回值为数组
     * @return array
     */
    public function getRuleIds($uid)
    {
        //读取用户所属用户组
        $groups = $this->getGroups($uid);
        $groups = json_decode($groups,true);
        $ids = []; //保存用户所属用户组设置的所有权限规则id

        foreach ($groups as $g)
        {
            $ids = array_merge($ids, explode(',', trim($g[$this->system.'_group_rule'], ',')));
        }

        $ids = array_unique($ids);
        return $ids;
    }

    /**
     * 根据用户id获取用户组,返回值为数组
     * @return array
     */
    protected function getGroups($uid)
    {
        static $groups = [];
        if (isset($groups[$uid]))
        {
            return $groups[$uid];
        }

        // 执行查询
        $user_groups = DB::table($this->config['auth_user'].' as au')
                        ->join($this->config['auth_group'].' as ag','ag.'.$this->system.'_group_id','=','au.'.$this->system.'_adm_group_id')
                        ->select('au.'.$this->system.'_adm_id','au.'.$this->system.'_adm_group_id','ag.'.$this->system.'_group_id','ag.'.$this->system.'_group_pid','ag.'.$this->system.'_group_name','ag.'.$this->system.'_group_rule')
                        ->where('au.'.$this->system.'_adm_id',$uid)
                        ->where('ag.'.$this->system.'_group_status',1)
                        ->get();

        $groups[$uid] = $user_groups ?: [];
        return $groups[$uid];
    }


    /**
     * 检查路由访问权限
     * @return boolean
     */
    public function mathUrl($url,$noNeedRight)
    {
        if(in_array("*",$noNeedRight)){
            //无需检查权限
            return true;
        }

        // 读取管理员当前拥有的权限节点
        $userRule = $this->getRuleList();

        //定义默认方法
        $defaultNoNeedRight = ['list','form','save','del'];

        //检查是否有权限访问
        foreach ($userRule as $v){
            if(strpos($url,$v) !== false){
                $currentArr = explode('/',$url);
                $str = array_pop($currentArr);

                //检查是否请求默认方法
                foreach ($defaultNoNeedRight as $value){
                    if(strpos($url,$value)){
                        return true;
                    }
                }

                if(in_array($str,$noNeedRight)){
                    return true;
                }else if($url == '/'.$v){
                    return true;
                }
            }
            else if(current($userRule) == '*'){
                return true;
            }
        }
        return false;
    }
}