<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <!-- 头部区域 -->
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item layadmin-flexible" lay-unselect>
                <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                    <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                </a>
            </li>
            <li class="layui-nav-item" lay-unselect>
                <a href="javascript:;" layadmin-event="refresh" title="刷新">
                    <i class="layui-icon layui-icon-refresh-3"></i>
                </a>
            </li>
        </ul>
        <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">

            <li class="layui-nav-item" lay-unselect>
                <a lay-href="task" layadmin-event="message">


                    <!-- 如果有安装任务真在运行，则显示加载圈 -->
                    <script type="text/html" template lay-url="/api/panel/task/getStatus">
                        @{{# if(d.data){ }}
                        <i class="layui-icon layui-icon-loading-1 layui-anim layui-anim-rotate layui-anim-loop"></i>
                        @{{# } else { }}
                        <i class="layui-icon layui-icon-component"></i>
                        @{{# } }}
                    </script>

                </a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a href="javascript:;" layadmin-event="theme">
                    <i class="layui-icon layui-icon-theme"></i>
                </a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a href="javascript:;" layadmin-event="note">
                    <i class="layui-icon layui-icon-note"></i>
                </a>
            </li>
            <li class="layui-nav-item layui-hide-xs" lay-unselect>
                <a href="javascript:;" layadmin-event="fullscreen">
                    <i class="layui-icon layui-icon-screen-full"></i>
                </a>
            </li>
            <li style="margin-right: 15px;" class="layui-nav-item" lay-unselect>
                <script type="text/html" template lay-url="/api/panel/user/getInfo"
                        lay-done="layui.element.render('nav', 'layadmin-layout-right');">
                    <a href="javascript:;">
                        <cite>@{{= d.data.username }}</cite>
                    </a>
                    <dl class="layui-nav-child">
                        <dd><a lay-href="setting">修改密码</a></dd>
                        <hr>
                        <dd lay-href="logout" style="text-align: center;"><a>退出</a></dd>
                    </dl>
                </script>
            </li>
        </ul>
    </div>

    <!-- 侧边菜单 -->
    <div class="layui-side layui-side-menu">
        <div class="layui-side-scroll">
            <script type="text/html" template lay-url="/api/panel/info/getMenu?v=@{{ layui.cache.version }}"
                    lay-done="layui.element.render('nav', 'layadmin-system-side-menu');" id="TPL_layout">

                <div class="layui-logo" lay-href="">
                    {{ config('panel.name') }}
                </div>

                <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu" lay-filter="layadmin-system-side-menu">
                    @{{#
                      var path =  layui.router().path
                      ,pathURL = layui.admin.correctRouter(path.join('/'))
                      ,dataName = layui.setter.response.dataName;

                      layui.each(d[dataName], function(index, item){
                        var hasChildren = typeof item.list === 'object' && item.list.length > 0
                        ,classSelected = function(){
                          var match = path[0] == item.name || (index == 0 && !path[0])
                          || (item.jump && pathURL == layui.admin.correctRouter(item.jump)) || item.spread;
                          if(match){
                            return hasChildren ? 'layui-nav-itemed' : 'layui-this';
                          }
                          return '';
                        }
                        ,url = (item.jump && typeof item.jump === 'string') ? item.jump : item.name;
                    }}
                    <li data-name="@{{= item.name || '' }}" data-jump="@{{= item.jump || '' }}" class="layui-nav-item @{{= classSelected() }}">
                        <a href="javascript:;" @{{- hasChildren ? '' : 'lay-href="'+ url +'"' }} lay-tips="@{{= item.title }}" lay-direction="2">
                            <i class="layui-icon @{{= item.icon }}"></i>
                            <cite>@{{= item.title }}</cite>
                        </a>
                        @{{# if(hasChildren){ }}
                        <dl class="layui-nav-child">
                            @{{# layui.each(item.list, function(index2, item2){
                              var hasChildren2 = typeof item2.list == 'object' && item2.list.length > 0
                              ,classSelected2 = function(){
                                var match = (path[0] == item.name && path[1] == item2.name)
                                || (item2.jump && pathURL == layui.admin.correctRouter(item2.jump)) || item2.spread;
                                if(match){
                                  return hasChildren2 ? 'layui-nav-itemed' : 'layui-this';
                                }
                                return '';
                              }
                              ,url2 = (item2.jump && typeof item2.jump === 'string')
                                ? item2.jump
                              : [item.name, item2.name, ''].join('/');
                            }}
                            <dd  data-name="@{{= item2.name || '' }}"  data-jump="@{{= item2.jump || '' }}"
                                    @{{- classSelected2() ? ('class="'+ classSelected2() +'"') : '' }}>
                                <a href="javascript:;" @{{- hasChildren2 ? '' : 'lay-href="'+ url2 +'"' }}>@{{= item2.title }}</a>
                                @{{# if(hasChildren2){ }}
                                <dl class="layui-nav-child">
                                    @{{# layui.each(item2.list, function(index3, item3){
                                      var match = (path[0] == item.name && path[1] == item2.name && path[2] == item3.name)
                                      || (item3.jump && pathURL == layui.admin.correctRouter(item3.jump))
                                      ,url3 = (item3.jump && typeof item3.jump === 'string')
                                        ? item3.jump
                                      : [item.name, item2.name, item3.name].join('/')
                                    }}
                                    <dd data-name="@{{= item3.name || '' }}"  data-jump="@{{= item3.jump || '' }}"
                                            @{{- match ? 'class="layui-this"' : '' }}>
                                        <a href="javascript:;" lay-href="@{{= url3 }}" @{{- item3.iframe ? 'lay-iframe="true"' : '' }}>@{{= item3.title }}</a>
                                    </dd>
                                    @{{# }); }}
                                </dl>
                                @{{# } }}
                            </dd>
                            @{{# }); }}
                        </dl>
                        @{{# } }}
                    </li>
                    @{{# }); }}
                </ul>
            </script>
        </div>
    </div>


    <!-- 页面标签 -->
    <script type="text/html" template lay-done="layui.element.render('nav', 'layadmin-pagetabs-nav')">
        @{{# if(layui.setter.pageTabs){ }}
        <div class="layadmin-pagetabs" id="LAY_app_tabs">
            <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
            <div class="layui-icon layadmin-tabs-control layui-icon-down">
                <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;"></a>
                        <dl class="layui-nav-child layui-anim-fadein">
                            <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                            <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                            <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>
            <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                <ul class="layui-tab-title" id="LAY_app_tabsheader">
                    <li lay-id="/"><i class="layui-icon layui-icon-home"></i></li>
                </ul>
            </div>
        </div>
        @{{# } }}
    </script>


    <!-- 主体内容 -->
    <div class="layui-body" id="LAY_app_body">
        <div class="layadmin-tabsbody-item layui-show"></div>
    </div>

    <!-- 辅助元素，一般用于移动设备下遮罩 -->
    <div class="layadmin-body-shade" layadmin-event="shade"></div>

</div>
