kodReady.push(function(){
	//右键菜单
	var menuShow = function(menu,app){
	    var _this = this;
        if(!menu.$menu.find('.BTUpload').exists() ) {
            $.contextMenu.menuAdd({
                'BTUpload': {
                    name: '添加服务器文件',
                    className: "upload-file",
                    icon: "ri-upload-fill-2 small",
                    callback: function (action,option) {
                        var url = '{{pluginApi}}'+'&path='+app.root.path.jsonData.current.path;
                        core.openDialog(url, core.icon('upload'), '添加服务器文件', 'BTUpload', {
                            width: '440px',
                            height: '540px',
                        });
                    },
                }
            }, menu, '', '.new-folder');
        }
	};
	Events.bind({
		'rightMenu.beforeShow@.menu-path-body':menuShow,
	});
});