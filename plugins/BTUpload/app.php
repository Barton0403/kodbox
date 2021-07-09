<?php

class BTUploadPlugin extends PluginBase{
	function __construct(){
		parent::__construct();
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
                $node['children'] = $this->createNode($dir.$file);
            }

            $z_nodes[] = $node;
        }

        return $z_nodes;
    }
}