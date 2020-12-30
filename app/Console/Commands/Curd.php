<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Curd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:curd {table} {--c=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a crud demo';

    protected $files;
    protected $type = 'curd';

    /**
     * Create a new command instance.
     *@param  \Illuminate\Filesystem\Filesystem  $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        //输入表名,不加公共前缀
        $table = $this->argument('table');
        $controller = $this->options('c');

        //参数c必须是项目名称
        $sysNames = array_keys(config("common.sys_name"));
        $arrController = explode('/',$controller['c']);

        if(!in_array(strtolower($arrController[0]), $sysNames)) {
            $this->error("请输入正确控制器参数");
            exit;
        }


        $c = $controller['c'];

        if (empty($table)) {
            $this->error("缺少参数table");
        }

        //下划线转大写
        $splitTable = explode('_',$table);
        $ucfristTable = implode('',array_map(function ($v){
            return ucfirst($v);
        },$splitTable));




        //执行迁移文件创建表
        Artisan::call("migrate");



        //判断表是否创建
        if(!Schema::hasTable($table))
        {
                $this->error("该表不存在，请先创建该表的迁移文件");
                return false;
        }




        //创建数据模型文件
        $this->makeModel($table, $ucfristTable);
        //创建request验证文件
        $this->makeRequest($table, $c);
        //创建控制器文件
        $this->makeController($table, $c);
        //创建数据仓库文修的
        $this->makeRepositories($table, $c);
        //创建view中的index.blade.php
        $this->makeIndexView($table, $c);

        //创建model
        //exec("php artisan make:model Models/".$splitTable);
    }

    /**
     * @param $table
     * @param $controller
     * @return mixed
     */
    protected function makeRequest($table,$controller)
    {
        $basePath = base_path();
        //$requestPath = $basePath.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR.'Http'.DIRECTORY_SEPARATOR.'Requests';

        //获取真实文件名
        $name = $this->qualifyClass($controller,'request');
        $path = $this->getPath($name);

        if ($this->files->exists($path))
        {
            $this->error("该验证器已存在");

            return false;
        }

        //创建所需的request文件 start ===============================================
        $this->makeDirectory($path);
        $stub_path = $basePath."/app/Console/Commands/Curd/Stubs/request.stub";
        //获取模板中的内容
        $stub = $this->files->get($stub_path);

        //替换掉模板的特殊标识
        $stub = $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);

        //解析数据库里的规则
        $tablePrefix = DB::getTablePrefix();
        $tableInfo = DB::select("SHOW FULL COLUMNS FROM ".$tablePrefix.$table);
        $tableInfo = array_map('get_object_vars', $tableInfo);

        //生成规则文本数据
        $rulesData = $this->buildRulesData($tableInfo);

        $stub = str_replace("{{rules}}",$rulesData,$stub);



        $this->files->put($path, $stub);

        $this->info('验证器创建成功');

        //创建所需的request文件 end  ===============================================
    }

    /**
     * @param $table
     * @param $controller
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeModel($table,$controller)
    {
        //创建所需的controller文件 start ===============================================
        $basePath = base_path();

        //下划线转大写
        $splitTable = explode('_',$table);
        $ucfristTable = implode('',array_map(function ($v){
            return ucfirst($v);
        },$splitTable));





        //获取真实文件名 //m1
        $name = $this->qualifyClass($controller,'model');




        $path = $this->getPath($name);

        if ($this->files->exists($path))
        {
            $this->error("该数据模型已存在");

            return false;
        }

        $this->makeDirectory($path);

        $controllerStubPath = $basePath."/app/Console/Commands/Curd/Stubs/model.stub";

        $controllerStub = $this->files->get($controllerStubPath);

        //将表明

        $controllerStub = $this->replaceNamespace($controllerStub, $name)->replaceClass($controllerStub, $name);

        //获取数据表主键
        $tablePrefix = DB::getTablePrefix();
        $tableInfo = DB::select("SHOW FULL COLUMNS FROM ".$tablePrefix.$table);
        $tableInfo = array_map('get_object_vars', $tableInfo);


        $primary_key = $this->getPriKey($tableInfo);


        /*$column_arr = Schema::getColumnListing($table);

        //将第一个列名作为主键
        $primary_key = $column_arr[0];*/

        //把#号标签全部替换掉
        $search = [
            '#primary_key#',
            '#table_name#',
        ];
        $replace = [
            $primary_key,
            $table
        ];


        $controllerStub = str_replace($search, $replace, $controllerStub);


        $this->files->put($path, $controllerStub);
        $this->info('Model创建成功.');

    }



    /**
     * @param $table
     * @param $controller
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeController($table,$controller)
    {
        //创建所需的controller文件 start ===============================================
        $basePath = base_path();
        //获取真实文件名
        $name = $this->qualifyClass($controller,'controller');
        $requestNamesapce = $this->qualifyClass($controller,'request');



        $path = $this->getPath($name);
        if ($this->files->exists($path))
        {
            $this->error("该控制器已存在");

            return false;
        }

        $this->makeDirectory($path);
        $controllerStubPath = $basePath."/app/Console/Commands/Curd/Stubs/controller.stub";

        $controllerStub = $this->files->get($controllerStubPath);

        $controllerStub = $this->replaceNamespace($controllerStub, $name)->replaceClass($controllerStub, $name);


        //下划线转大写
        $splitTable = explode('_',$table);
        $ucfristTable = implode('',array_map(function ($v){
            return ucfirst($v);
        },$splitTable));

        $viewPath = strtolower(str_replace('/','.',$controller));
        $arrController = explode('/',$controller);
        $disController = array_pop($arrController);

        //获取base类登命名空间
        $arrController = explode('/',$controller);
        $baseNamespace = "App\\Http\\Controllers\\".$arrController[0]."\\BaseController";



        //把#号标签全部替换掉
        $search = [
            '#request_namespace#',
            '#table#',
            '#view_path#',
            '#controller#',
            '#base_controller#'
        ];
        $replace = [
            $requestNamesapce,
            $ucfristTable,
            $viewPath,
            $disController,
            $baseNamespace
        ];



        $controllerStub = str_replace($search, $replace, $controllerStub);

        $this->files->put($path, $controllerStub);
        $this->info('控制器创建成功.');

        //创建所需的controller文件 end ==================================================
    }

    /**
     * 创建仓库文件
     * @param $table
     * @param $controller
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeRepositories($table,$controller)
    {
        //创建所需的repositories文件 start ===============================================
        $basePath = base_path();
        $splitTable = explode('_',$table);
        $ucfristTable = implode('',array_map(function ($v){
            return ucfirst($v);
        },$splitTable));
        $reposNamespace = "App\\Repositories\\".$ucfristTable."Repository";
        $path = $this->getPath($reposNamespace);


        if ($this->files->exists($path))
        {
            $this->error("该仓库已存在");

            return false;
        }

        $this->makeDirectory($path);
        $reposPath = $basePath."/app/Console/Commands/Curd/Stubs/repositories.stub";

        $reposStub = $this->files->get($reposPath);
        $reposStub = $this->replaceNamespace($reposStub, $reposNamespace)->replaceClass($reposStub, $reposNamespace);

        $tablePrefix = DB::getTablePrefix();
        $tableInfo = DB::select("SHOW FULL COLUMNS FROM ".$tablePrefix.$table);
        $tableInfo = array_map('get_object_vars', $tableInfo);
        $priKey = $this->getPriKey($tableInfo);

        $searchRepos = [
            '#model#',
            '#pri_key#'
        ];
        $replaceRepos = [
            $ucfristTable,
            $priKey
        ];
        $reposStub = str_replace($searchRepos, $replaceRepos, $reposStub);
        $this->files->put($path, $reposStub);
        $this->info('仓库创建成功.');

        //创建所需的repositories文件 end =================================================
    }

    /**
     * 创建index.blade.php
     * @param $table
     * @param $controller
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeIndexView($table,$controller)
    {
        $basePath = base_path();
        $viewPath = $this->getViewPath($controller).DIRECTORY_SEPARATOR."index.blade.php";

        $this->makeDirectory($viewPath);
        $viewStubPath = $reposPath = $basePath."/app/Console/Commands/Curd/Stubs/view/index.stub";


        $indexStub = $this->files->get($viewStubPath);

        $viewStr = str_replace("/", '.',strtolower($controller));

        $formUrl = explode('/', strtolower($controller));
        array_shift($formUrl);

        $strFormUrl = '/'.implode('/',$formUrl).'/form';
        $strTblUrl = '/'.implode('/',$formUrl).'/list';

        //获取表头数据
        $tablePrefix = DB::getTablePrefix();
        $tableInfo = DB::select("SHOW FULL COLUMNS FROM ".$tablePrefix.$table);
        $tableInfo = array_map('get_object_vars', $tableInfo);

        $tableHeadData = $this->getViewTableData($tableInfo);

        $search = [
            '#comp_search#', //搜索组件
            '#url_form#',    //form表单url
            '#url_tbl_list#', //table数据url
            '#table_head#',
        ];
        $replace = [
            $viewStr."._search",
            $strFormUrl,
            $strTblUrl,
            $tableHeadData['comment']
        ];
        $indexStub = str_replace($search, $replace, $indexStub);

        if ($this->files->exists($viewPath))
        {
            $this->error("该index视图已存在");

        }else{
            $this->files->put($viewPath, $indexStub);

            $this->info('创建index视图成功成功.');
        }




        //创建index中的search组件
        $this->makeSearchView($table,$controller,$tableInfo);


        //创建index中的table组件
        $this->makeTableView($table,$controller,$tableInfo);

        //创建index中的form组件
        $this->makeFormView($table,$controller,$tableInfo);


    }

    /**
     * 创建视图中的search
     * @param $table
     * @param $controller
     * @param $tableInfo
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeSearchView($table,$controller,$tableInfo)
    {
        $basePath = base_path();
        $searchPath = $this->getViewPath($controller).DIRECTORY_SEPARATOR."_search.blade.php";

        $this->makeDirectory($searchPath);
        $viewStubPath = $reposPath = $basePath."/app/Console/Commands/Curd/Stubs/view/search.stub";

        //search的视图文本数据
        $searchStub = $this->files->get($viewStubPath);

        //search中form片段(这里可以再细分为input select datapicker....)
        $searchFormPath = $basePath."/app/Console/Commands/Curd/Stubs/view/search/form.stub";
        $searchFormStub = $this->files->get($searchFormPath);

        //搜索插件更多的表单
        $searchFormMorePath = $basePath."/app/Console/Commands/Curd/Stubs/view/search/form_more.stub";
        $searchFormMoreStub = $this->files->get($searchFormMorePath);


        //基础搜索三个，剩下的作为扩展的
        $baseFormHtml = '';
        $moreFormHtml = '';
        $moreStr = '';
        $headNum = 0;
        $moreNum = 0;

        $tableData = $this->getViewTableData($tableInfo);

        foreach ($tableData['field'] as $tk=>$tv) {
            $formSearch = [
                "#feild_name#",
                "#feild_value#",
                "#feild_id#"
            ];
            $formReplace = [
                $tableData['comm'][$tk],
                $tv,
                $tv,
            ];
            $formStr = str_replace($formSearch, $formReplace, $searchFormStub);
            if($tk<3) { //前三个
                $baseFormHtml.=$formStr.PHP_EOL;
                $headNum++;
            } else {    //后面的
                $moreStr .= $formStr.PHP_EOL;
                //每隔3个包一层<div>
                if($tk%3==2 && $tk>3) {

                    $moreFormHtml.=str_replace("#form_stub#", $moreStr,$searchFormMoreStub).PHP_EOL;
                    $moreStr = '';
                }
                $moreNum++;

            }
        }

        //剩下的加上
        $moreFormHtml.=str_replace("#form_stub#", $moreStr,$searchFormMoreStub).PHP_EOL;


        //把整体替换掉search里面的标识
        $allSearch = [
            "#main_search#",
            "#more_search#"
        ];

        $allReplace = [
            $baseFormHtml,
            $moreFormHtml
        ];

        $searchStub = str_replace($allSearch, $allReplace, $searchStub);


        if ($this->files->exists($searchPath))
        {
            $this->error("该search视图已存在");

        }else{
            $this->files->put($searchPath, $searchStub);
            $this->info('--创建search视图成功.');
        }


    }

    /**
     * 创建table视图
     * @param $table
     * @param $controller
     * @param $tableInfo
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeTableView($table,$controller,$tableInfo)
    {
        $basePath = base_path();
        $tablePath = $this->getViewPath($controller).DIRECTORY_SEPARATOR."_table.blade.php";

        $this->makeDirectory($tablePath);
        $viewStubPath = $reposPath = $basePath."/app/Console/Commands/Curd/Stubs/view/table.stub";

        //search的视图文本数据
        $tableStub = $this->files->get($viewStubPath);

        $tableData = $this->getViewTableData($tableInfo);

        $html = '';
        $num = 0;
        foreach ($tableData['field'] as $k=>$v) {
            if($num>0) {
                $html .= "\t\t";
            }
            $html.="<td>{{\$v['".$v."']}}</td>".PHP_EOL;
            $num++;
        }

        $formUrl = explode('/', strtolower($controller));
        array_shift($formUrl);

        $strFormUrl = '/'.implode('/',$formUrl).'/form';
        $strDelUrl = '/'.implode('/',$formUrl).'/del';
        $priKey = $this->getPriKey($tableInfo);


        $search = [
            "#table_data#",
            "#edit_url#",
            "#del_url#",
            "#field_num#",
            "#pri_key#"
        ];
        $replace = [
            $html,
            $strFormUrl,
            $strDelUrl,
            $num+1,
            $priKey
        ];

        $tableStub = str_replace($search, $replace, $tableStub);

        if ($this->files->exists($tablePath))
        {
            $this->error("该table视图已存在");

        }else{
            $this->files->put($tablePath, $tableStub);
            $this->info('--创建table视图成功.');
        }

    }

    /**
     * 创建form视图
     * @param $table
     * @param $controller
     * @param $tableInfo
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function makeFormView($table,$controller,$tableInfo)
    {
        $basePath = base_path();
        $formPath = $this->getViewPath($controller).DIRECTORY_SEPARATOR."_form.blade.php";

        $this->makeDirectory($formPath);
        $viewStubPath = $reposPath = $basePath."/app/Console/Commands/Curd/Stubs/view/form.stub";
        $viewPathStub = $this->files->get($viewStubPath);

        //form项中的view数据
        $formItemPath = $basePath."/app/Console/Commands/Curd/Stubs/view/form/item.stub";
        $formItemStub = $this->files->get($formItemPath);

        //循环数据项
        $tableData = $this->getViewTableData($tableInfo);
        $itemHtml = '';
        $num = 0;
        foreach ($tableData['field'] as $k=>$v) {
            $item_search = [
                "#form_title#",
                "#form_field#"
            ];

            $item_replace = [
                $tableData['comm'][$k],
                $v
            ];
            $formItemStr = str_replace($item_search, $item_replace, $formItemStub);

            $itemHtml.= $formItemStr.PHP_EOL;
        }

        $saveUrl = explode('/', strtolower($controller));
        array_shift($saveUrl);

        $strSaveUrl = '/'.implode('/',$saveUrl).'/save';

        $priKey = $this->getPriKey($tableInfo);
        $allSearch = [
            "#form_data#",
            "#save_url#",
            "#pri_key#"
        ];
        $allReplace = [
            $itemHtml,
            $strSaveUrl,
            $priKey
        ];

        $formItemStub = str_replace($allSearch, $allReplace, $viewPathStub);

        if ($this->files->exists($formPath))
        {
            $this->error("该form视图已存在");

        }else{
            $this->files->put($formPath, $formItemStub);
            $this->info('--创建form视图成功.');
        }

    }

    /**
     * 获取表的主键
     * @param $tableInfo
     * @return string
     */
    protected function getPriKey($tableInfo)
    {
        //获取表的第一个主键
        $priKey = 'id';
        foreach ($tableInfo as $tk=>$tv) {
            if($tv['Key'] == 'PRI') {
                $priKey = $tv['Field'];
                break;
            }
        }
        return $priKey;
    }

    /**
     * 获取view所在目录
     * @param $controller
     * @return string
     */
    protected function getViewPath($controller)
    {
        $basePath = base_path();
        $viewPath = $basePath.DIRECTORY_SEPARATOR."resources".DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.strtolower($controller);
        return $viewPath;
    }
    /**
     * @return mixed
     * 获取根命名空间
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * 生成目标文件路径
     * @param $name 传递过来的名称 如 Agent/test
     * @return string
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * 默认命名空间
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultRequestNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Requests';
    }

    /**
     * 默认控制器命名空间
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultControllerNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }
    /**
     * 默认Model命名空间
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultModelNamespace($rootNamespace)
    {
        return $rootNamespace.'\Models';
    }



    /**
     * 获取有效的文件名。
     * @param $name
     * @param $modules 模块 controller/model/request
     * @return mixed|string
     */
    protected function qualifyClass($name,$modules)
    {
        $name = ltrim($name, '\\/');


        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        $name = str_replace('/', '\\', $name);

        switch ($modules) {
            case "request" :
                return $this->qualifyClass(
                    $this->getDefaultRequestNamespace(trim($rootNamespace, '\\')).'\\'.$name.ucfirst($modules),$modules
                );
            break;
            case "controller":
                return $this->qualifyClass(
                    $this->getDefaultControllerNamespace(trim($rootNamespace, '\\')).'\\'.$name.ucfirst($modules),$modules
                );
            break;
            case "model":
                return $this->qualifyClass(
                    $this->getDefaultModelNamespace(trim($rootNamespace, '\\')).'\\'.$name,$modules
                );
                break;

        }


    }


    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $stub = str_replace(
            ['DummyNamespace', 'DummyRootNamespace'],
            [$this->getNamespace($name), $this->rootNamespace()],
            $stub
        );

        return $this;
    }

    /**
     * Replace the class name for the given stub.
     *
     * @param  string  $stub
     * @param  string  $name
     * @return string
     */
    protected function replaceClass($stub, $name)
    {

        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('DummyClass', $class, $stub);
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     * @return string
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }


    /**
     * 创建request中的rules()方法中的数据
     * @param $params
     * @return string
     */
    protected function buildRulesData($params)
    {
        $html = "";
        $num = 0;
        foreach ($params as $k=>$v) {

            $noRules = ['id', 'created_at', 'updated_at'];
            if(in_array($v['Field'], $noRules) || $v['Extra'] == 'auto_increment') {  //过滤掉id
                continue;
            }
            if($num>0) {
                $html .= "\t\t\t";
            }

            $html.="'".$v['Field']."' => '";
            if($v['Null'] == 'NO') {
                $html.="required";
            }

            if(strpos($v['Type'],'int') !==false) {
                $html.="|integer";
            }

            if(strpos($v['Type'],'varchar') !==false) {
                //获取varchar长度
                if(preg_match('/\d+/',$v['Type'],$arr)){
                    $len =  $arr[0];
                    $html.="|max:".$len;
                }
            }
            $html.="',".PHP_EOL;
            $num++;
        }
        return $html;

    }

    /**
     * 获取表头数据
     * @param $params
     * @return array
     */
    protected function getViewTableData($params)
    {
        $html = "";
        $fields = [];  //可用字段
        $comment = []; //对应的注释
        $num = 0;
        foreach ($params as $k=>$v) {
            if($num>0) {
                $html .= "\t\t\t\t\t";
            }
            if($v['Null'] == 'NO' && !empty($v['Comment'])) { //获取非空数据作为表并头
                $html.="<td>".$v['Comment']."</td>".PHP_EOL;
                $num++;
                $fields[] = $v['Field'];
                $comment[] = $v['Comment'];
            }
        }
        return ['comment' => $html, 'field' => $fields, 'comm' =>$comment  ];
    }

   
}
