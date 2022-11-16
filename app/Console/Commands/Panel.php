<?php

namespace App\Console\Commands;

use App\Models\Plugin;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Panel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'panel {action?} {a1?} {a2?} {a3?} {a4?} {a5?} {a6?} {a7?} {a8?} {a9?} {a10?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '耗子Linux面板命令行工具';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $action = $this->argument('action');
        switch ($action) {
            case 'update':
                $this->update();
                break;
            case 'getInfo':
                $this->getInfo();
                break;
            case 'writePluginInstall':
                $this->writePluginInstall();
                break;
            case 'writePluginUnInstall':
                $this->writePluginUnInstall();
                break;
            case 'writePluginUpdate':
                $this->writePluginUpdate();
                break;
            default:
                $this->error('错误的操作');
                break;
        }
        return Command::SUCCESS;
    }

    /**
     * 更新面板
     * @return void
     */
    private function update(): void
    {
        $this->info('正在下载面板...');
        $this->info(shell_exec('wget -O /tmp/panel.zip https://api.panel.haozi.xyz/api/version/latest'));
        $this->info('正在备份数据库...');
        $this->info(shell_exec('\cp /www/panel/database/database.sqlite /tmp/database.sqlite'));
        $this->info('正在备份插件...');
        $this->info(shell_exec('rm -rf /tmp/plugins'));
        $this->info(shell_exec('mkdir /tmp/plugins'));
        $this->info(shell_exec('\cp -r /www/panel/plugins/* /tmp/plugins'));
        $this->info('正在删除旧版本...');
        $this->info(shell_exec('rm -rf /www/panel'));
        $this->info(shell_exec('mkdir /www/panel'));
        $this->info('正在解压新版本...');
        $this->info(shell_exec('unzip /tmp/panel.zip -d /www/panel'));
        $this->info('正在恢复数据库...');
        $this->info(shell_exec('\cp /tmp/database.sqlite /www/panel/database/database.sqlite'));
        $this->info('正在恢复插件...');
        $this->info(shell_exec('\cp -r /tmp/plugins/* /www/panel/plugins'));
        $this->info('正在清理临时文件...');
        $this->info(shell_exec('rm -rf /tmp/panel.zip'));
        $this->info(shell_exec('rm -rf /tmp/database.sqlite'));
        $this->info(shell_exec('rm -rf /tmp/plugins'));
        $this->info('正在更新数据库...');
        $this->info(shell_exec('cd /www/panel && php-panel artisan migrate'));
        $this->info('正在重启面板服务...');
        $this->info(shell_exec('systemctl reload panel.service'));
        $this->info('更新完成');
    }

    /**
     * 获取面板信息
     * @return void
     */
    private function getInfo(): void
    {
        $user = User::query()->where('id', 1);
        // 判空
        if (empty($user)) {
            $this->error('获取失败');
            return;
        }
        // 生成唯一信息
        $username = Str::random(6);
        $password = Str::random(12);
        // 入库
        $user->update([
            'username' => $username,
            'password' => Hash::make($password),
        ]);
        $this->info('面板用户名：' . $username);
        $this->info('面板密码：' . $password);
        $this->info('访问地址：http://IP:8888');
    }

    /**
     * 写入插件安装状态
     * @return void
     */
    private function writePluginInstall(): void
    {
        $pluginSlug = $this->argument('a1');
        $pluginName = $this->argument('a2');
        $pluginVersion = $this->argument('a3');

        // 判空
        if (empty($pluginSlug) || empty($pluginName) || empty($pluginVersion)) {
            $this->error('参数错误');
            return;
        }
        // 入库
        Plugin::query()->create([
            'slug' => $pluginSlug,
            'name' => $pluginName,
            'version' => $pluginVersion,
            'show' => 0,
        ]);
        $this->info('成功');
    }

    /**
     * 写入插件卸载状态
     * @return void
     */
    private function writePluginUnInstall(): void
    {
        $pluginSlug = $this->argument('a1');

        // 判空
        if (empty($pluginSlug)) {
            $this->error('参数错误');
            return;
        }
        if ($pluginSlug == 'openresty') {
            $this->error('耗子Linux面板：请不要花样作死！');
            return;
        }
        // 入库
        Plugin::query()->where('slug', $pluginSlug)->delete();
        $this->info('成功');
    }

    /**
     * 写入插件更新状态
     * @return void
     */
    private function writePluginUpdate(): void
    {
        $pluginSlug = $this->argument('a1');
        $pluginVersion = $this->argument('a2');

        // 判空
        if (empty($pluginSlug) || empty($pluginVersion)) {
            $this->error('参数错误');
            return;
        }
        // 入库
        Plugin::query()->where('slug', $pluginSlug)->update([
            'version' => $pluginVersion,
        ]);
        $this->info('成功');
    }
}
