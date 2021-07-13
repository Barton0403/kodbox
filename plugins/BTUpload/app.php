<?php

use think\facade\Db;

class BTUploadPlugin extends PluginBase{
	function __construct(){
		parent::__construct();

        Db::setConfig([
            // 默认数据连接标识
            'default'     => 'mysql',
            // 数据库连接信息
            'connections' => [
                'mysql' => [
                    // 数据库类型
                    'type'     => 'mysql',
                    // 主机地址
                    'hostname' => $this->config['database']['DB_HOST'],
                    // 用户名
                    'username' => $this->config['database']['DB_USER'],
                    'password' => $this->config['database']['DB_PWD'],
                    'hostport' => $this->config['database']['DB_PORT'],
                    // 数据库名
                    'database' => $this->config['database']['DB_NAME'],
                    // 数据库编码默认采用utf8
                    'charset'  => 'utf8',
                    // 数据库表前缀
                    'prefix'   => '',
                    // 数据库调试模式
                    'debug'    => false,
                ],
            ]
        ]);
	}

	public function regist(){
		$this->hookRegist(array(
			'user.commonJs.insert' => 'BTUploadPlugin.echoJs',
		));
	}
    public function echoJs(){
        $this->echoFile('static/main.js');
    }

    public function index()
    {
        $path = KodIO::parse($this->in['path']);

        $dir = DATA_PATH.'files/downloads/';
        $z_nodes = $this->createNode($dir);

        include($this->pluginPath.'php/template.php');
        return;
    }

    private function createNode($dir)
    {
        $files = scandir($dir);
        $z_nodes = [];
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }

            $node = [
                'name' => $file,
                'isParent' => false,
            ];

            if (is_dir($dir.$file)) {
                $node['isParent'] = true;
                $node['children'] = $this->createNode($dir.'/'.$file);
            } else {
                $node['path'] = str_replace(DATA_PATH.'files/', '{io:1}/', $dir.'/'.$file);
            }

            $z_nodes[] = $node;
        }

        return $z_nodes;
    }

    public function add()
    {
        $path = $_POST['path'];
        echo '{}';
        return;
    }
}