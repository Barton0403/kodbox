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

        $dir = DATA_PATH.'files/';
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
                $node['children'] = $this->createNode($dir.$file.'/');
            } else {
                $node['path'] = str_replace(DATA_PATH.'files/', '{io:1}/', $dir.$file);
            }

            $z_nodes[] = $node;
        }

        return $z_nodes;
    }

    public function add()
    {
        $path = $_POST['path'];
        $source = $_POST['source'];

        $truepath = str_replace('{io:1}/', DATA_PATH.'files/', $path);
        $handle = fopen($truepath, "r");
        $fstat = fstat($handle);
        fclose($handle);

        $now = time();
        Db::startTrans();
        try {
            $file_id = Db::name('io_file')->insertGetId([
                'name' => basename($truepath),
                'size' => $fstat['size'],
                'ioType' => 1,
                'path' => $path,
                'hashSimple' => 1,
                'hashMd5' => 1,
                'linkCount' => 1,
                'createTime' => $now,
                'modifyTime' => $now,
            ]);
            $parent_level = Db::name('io_source')->where(['sourceID' => $source])->value('parentLevel').$source.',';
            Db::name('io_source')->insert([
                'sourceHash' => 1,
                'targetType' => 1,
                'targetID' => 2,
                'createUser' => 2,
                'modifyUser' => 2,
                'isFolder' => 0,
                'name' => basename($truepath),
                'fileType' => end(explode('.', basename($truepath))),
                'parentID' => $source,
                'parentLevel' => $parent_level,
                'fileID' => $file_id,
                'isDelete' => 0,
                'size' => $fstat['size'],
                'createTime' => $now,
                'modifyTime' => $now,
                'viewTime' => $now,
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            echo json_encode(['code' => 400, 'msg' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['code' => 200, 'msg' => '添加成功'], JSON_UNESCAPED_UNICODE);
        return;
    }
}