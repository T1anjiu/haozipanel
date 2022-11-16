<title>应用</title>

<div class="layui-fluid">
    <div class="layui-card">
        <!--<div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="store-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">搜索</label>
                    <div class="layui-input-block">
                        <input type="text" name="store_search" placeholder="请输入关键词（如php）" autocomplete="off"
                               class="layui-input">
                    </div>
                </div>
                <div class="layui-inline">
                    <button class="layui-btn layuiadmin-btn-order" lay-submit lay-filter="store-search-submit">
                        <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                    </button>
                </div>
            </div>
        </div>-->
        <div class="layui-card-body">
            <table id="panel-store" lay-filter="panel-store"></table>
            <!-- 操作按钮模板 -->
            <script type="text/html" id="store-control-tpl">
                @{{#  if(d.control.installed == true && d.control.allow_uninstall == true){ }}
                    @{{#  if(d.control.update == true){ }}
                    <a class="layui-btn layui-btn-xs" lay-event="update">更新</a>
                    @{{#  } }}
                <a class="layui-btn layui-btn-xs" lay-event="open">管理</a>
                <a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="uninstall">卸载</a>
                @{{#  } else{ }}
                @{{#  if(d.control.installed == true && d.control.allow_uninstall == false){ }}
                    @{{#  if(d.control.update == true){ }}
                    <a class="layui-btn layui-btn-xs" lay-event="update">更新</a>
                    @{{#  } }}
                <a class="layui-btn layui-btn-xs" lay-event="open">管理</a>
                @{{#  } else{ }}
                <a class="layui-btn layui-btn-xs" lay-event="install">安装</a>
                @{{#  } }}
                @{{#  } }}
            </script>
            <!-- 首页显示开关 -->
            <script type="text/html" id="plugin-show">
                <input type="checkbox" name="plugin-show-home" lay-skin="switch" lay-text="ON|OFF"
                       lay-filter="plugin-show-home"
                       value="@{{ d.show }}" data-plugin-slug="@{{ d.slug }}"
                        @{{ d.run==
                        1 ? 'checked' : '' }} />
            </script>
        </div>
    </div>
</div>

<script>
    layui.use(['admin', 'table', 'jquery'], function () {
        var $ = layui.$
            , form = layui.form
            , table = layui.table
            , admin = layui.admin;

        table.render({
            elem: '#panel-store'
            , url: '/api/panel/plugin/getList'
            , cols: [[
                {field: 'slug', hide: true, title: 'Slug', sort: true}
                , {field: 'name', width: '10%', title: '插件名'}
                , {field: 'describe', width: '48%', title: '描述'}
                , {field: 'install_version', width: '12%', title: '已装版本'}
                , {field: 'version', width: '12%', title: '最新版本'}
                , {field: 'show', title: '首页显示', width: 90, templet: '#plugin-show', unresize: true}
                , {field: 'control', title: '操作', templet: '#store-control-tpl', fixed: 'right', align: 'center'}
            ]]
            , page: false
            , text: '耗子Linux面板：数据加载出现异常！'
            , done: function () {
                //element.render('progress');
            }
        });

        //工具条
        table.on('tool(panel-store)', function (obj) {
            let data = obj.data;
            if (obj.event === 'open') {
                location.hash = '/plugin/' + data.slug;
            } else if (obj.event === 'install') {
                layer.confirm('确定安装该插件吗？', function (index) {
                    layer.close(index);
                    admin.req({
                        url: '/api/panel/install',
                        type: 'POST',
                        data: {
                            slug: data.slug
                        }
                        , success: function (res) {
                            if (res.code == 0) {
                                layer.msg('安装：' + data.name + ' 成功加入任务队列', {icon: 1, time: 1000}, function () {
                                    location.reload();
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2, time: 1000});
                            }
                        }
                        , error: function (xhr, status, error) {
                            console.log('耗子Linux面板：ajax请求出错，错误' + error)
                        }
                    });
                });
            } else if (obj.event === 'uninstall') {
                layer.confirm('确定卸载该插件吗？', function (index) {
                    layer.close(index);
                    admin.req({
                        url: '/api/panel/uninstall',
                        type: 'POST',
                        data: {
                            slug: data.slug
                        }
                        , success: function (res) {
                            if (res.code == 0) {
                                layer.msg('卸载：' + data.name + ' 成功！', {icon: 1, time: 1000}, function () {
                                    location.reload();
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2, time: 1000});
                            }
                        }
                        , error: function (xhr, status, error) {
                            console.log('耗子Linux面板：ajax请求出错，错误' + error)
                        }
                    });
                });
            } else if (obj.event === 'update') {
                layer.confirm('确定升级该插件吗？', function (index) {
                    layer.close(index);
                    admin.req({
                        url: '/api/panel/update',
                        type: 'POST',
                        data: {
                            slug: data.slug
                        }
                        , success: function (res) {
                            if (res.code == 0) {
                                layer.msg('安装：' + data.name + ' 成功加入任务队列', {icon: 1, time: 1000}, function () {
                                    location.reload();
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2, time: 1000});
                            }
                        }
                        , error: function (xhr, status, error) {
                            console.log('耗子Linux面板：ajax请求出错，错误' + error)
                        }
                    });
                });
            }
        });

        form.on('switch(plugin-show-home)', function (obj) {
            let $ = layui.$;
            let plugin_slug = $(this).data('plugin-slug');
            let show = obj.elem.checked ? 1 : 0;

            console.log(plugin_slug); //当前行数据
        });
        /*form.render(null, 'store-form');

        //搜索
        form.on('submit(store-search-submit)', function (data) {
            var field = data.field;

            //执行重载
            table.reload('store-search-submit', {
                where: field
            });
        });*/
    });
</script>
