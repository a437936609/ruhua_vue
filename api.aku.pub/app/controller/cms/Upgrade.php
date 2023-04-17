<?php

namespace app\controller\cms;

use ruhua\exceptions\BaseException;
use think\facade\Log;
use ruhua\version_update\Cloud;
use ruhua\version_update\Dir;
use ruhua\version_update\PclZip;
use ruhua\bases\BaseController;
use think\facade\Db;
use think\facade\Config;
use think\facade\Env;

class Upgrade extends BaseController
{
    public $identifier = 0;
    public $appVersion = '';
    protected function initialize() {
        parent::initialize();

        $this->rootPath         = VAE_ROOT;
        $this->appPath          = VAE_ROOT.'/app/';
        $this->updatePath       = $this->rootPath.'backup/uppack/';
        $this->updateBackPath   = $this->rootPath.'backup/upback/';
        $this->cloud            = new Cloud($this->updatePath);
        $this->cacheUpgradeList = 'upgrade_version_list'.$this->identifier;

        $this->appVersion = config::get('version.ruhua.version');

        if (!$this->appVersion) {
            return app('json')->fail('没有新版本');
        }
    }

    public function download($version = '')
    {
        if (!$this->request->isPost()) {
            return app('json')->fail('参数传递错误');
        }

        if (empty($version)) {
            return app('json')->fail('参数传递错误');
        }

        if (!is_dir($this->updatePath)) {
            Dir::create($this->updatePath, 0755);
        }

        $lock = $this->updatePath.$this->identifier.'upgrade.lock';
        if (!is_file($lock)) {
            //file_put_contents($lock, time());
        } else {
            return app('json')->fail('升级任务执行中，请手动删除此文件后重试！<br>文件地址：/uppack/'.$this->identifier.'upgrade.lock');
        }

        $versions = $this->getVersion1();
        // 检查当前升级补丁前面是否还有未升级的补丁
        $tobe = [];
        $file = '';
        //$file = $this->cloud->data(['version' => '12.0.11'])->down('/upgrade/get_down');
        foreach ($versions['data'] as $k => $v) {
            if (version_compare($k, $version, '>=')) {
                if (version_compare($k, $version, '=')) {
                    $file = $this->cloud->data(['version' => $k])->down('ruhua/upgrade/get_down');
                    break;
                }

            } else {
                $file = $this->cloud->data(['version' => $k])->down('ruhua/upgrade/get_down');

                if ($file === false) {
                    $this->clearCache($file);
                    return app('json')->fail('前置版本 '.$k.' 升级失败');
                } else {
//                    if (self::_install($file, $k, $this->appType) === false) {
//                        $this->clearCache($file);
//                        return app('json')->fail($this->error);
//                    }
                }
            }
        }
        if ($file === false || empty($file)) {
            $this->clearCache($file);
            return app('json')->fail('获取升级包失败');
        }
        return app('json')->go(['file'=>basename($file)]);
    }

  
    public function install($file = '', $version = '')
    {
        if (!$this->request->isPost()) {
            return $this->error('参数传递错误');
        }
        $file = $this->updatePath.$file;
        if (!file_exists($file)) {
            $this->clearCache($file);
            return $this->error($version.' 升级包异常，请重新升级');
        }

        if (self::_install($file, $version, $this->appType) === false) {
            $this->clearCache($file);
            return $this->error($this->error);
        }
        $jumpUrl = '';
        if ($this->appType == 'theme') {
            $param                  = $this->request->param('');
            $param['app_version']   = $param['version'];
            $param['app_name']      = cookie('upgrade_app_name');
            unset($param['file'], $param['version']);
            $jumpUrl = url('lists?'.http_build_query($param));
        }
        return $this->success('升级包安装成功', $jumpUrl);
    }

    public function _systemInstall($file, $version)
    {
        $_version = cache($this->cacheUpgradeList);
        if (!is_dir($this->updateBackPath)) {
            Dir::create($this->updateBackPath);
        }
        $decomPath = $this->updatePath.basename($file,".zip");
        if (!is_dir($decomPath)) {
            Dir::create($decomPath, 0777);
        }
        // 解压升级包
        $archive = new PclZip();
        $archive->PclZip($this->updatePath.$file.'.zip');
        if(!$archive->extract(PCLZIP_OPT_PATH, $decomPath, PCLZIP_OPT_REPLACE_NEWER)) {
            $this->error = '升级失败，请开启[/backup/uppack]文件夹权限';
            return app('json')->fail($this->error);
        }
        // 备份需要升级的旧版本
        $upInfo = include_once $decomPath.'/upgrade.php';
        $backPath = $this->updateBackPath.config::get('ruhua.version').'/';
        if (!is_dir($backPath)) {
            Dir::create($backPath, 0777);
        }

        array_push($upInfo['update'], '/version.php');
        //备份旧文件
        foreach ($upInfo['update'] as $k => $v) {
            $v = trim($v, '/');
            $_dir = $backPath.dirname($v).'/';
            if (!is_dir($_dir)) {
                Dir::create($_dir, 0777);
            }

            if ($v == '/composer.json') {
                $newComposer = json_decode(file_get_contents($decomPath.'/upload/composer.json'), 1);
                $oldComposer = json_decode(file_get_contents($this->rootPath.'composer.json'), 1);
                foreach($newComposer['require'] as $kk => $vv) {
                    $oldComposer['require'][$kk] = $vv;
                }
                @file_put_contents($decomPath.'/upload/composer.json', json_encode($oldComposer, 1));
            }

            if (is_file($this->rootPath.$v)) {
                @copy($this->rootPath.$v, $_dir.basename($v));
            }
        }

        // 根据升级补丁删除文件
        if (isset($upInfo['delete'])) {
            foreach ($upInfo['delete'] as $k => $v) {
                $v = trim($v, '/');
                if (is_file($this->rootPath.$v)) {
                    @unlink($this->rootPath.$v);
                }
            }
        }
        // 更新升级文件
        Dir::copyDir($decomPath.'/upload', $this->rootPath);

        // 导入SQL
        $sqlFile = realpath($decomPath.'/main.sql');
        Log::error($sqlFile);
        if (is_file($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            $sqlList = parse_sql($sql, 0, ['rh_' => 'rh_']);
            if ($sqlList) {
                $sqlList = array_filter($sqlList);
                foreach ($sqlList as $v) {

                    try {
                        Db::execute($v);
                    } catch(\Exception $e) {
                        throw new BaseException(['msg'=>'数据库更新失败']);
                        //$this->error = 'SQL更新失败';
                        return app('json')->fail();
                    }
                }
            }
        }
        $this->clearCache('', $version);
        return app('json')->success();
    }



    public function getVersion()
    {
        $cache = cache($this->cacheUpgradeList);
        if (isset($cache['data']) && !empty($cache['data'])) {
            return $cache;
        }

        $result = $this->cloud->data(['version' => $this->appVersion])->api('ruhua/upgrade/get_list');
        if ($result['status'] != 200) {
            cache($this->cacheUpgradeList, $result, 3600);  
        }
        $result['vae_version']=VAE_VERSION;
        return app('json')->go(['data'=>$result['data'],'vae_version'=>$result['vae_version']]);
    }
    public function getVersion1()
    {
        $cache = cache($this->cacheUpgradeList);
        if (isset($cache['data']) && !empty($cache['data'])) {
            return $cache;
        }

        $result = $this->cloud->data(['version' => $this->appVersion])->api('ruhua/upgrade/get_list');
        if ($result['status'] != 200) {
            cache($this->cacheUpgradeList, $result, 3600);
        }

        return $result;
    }

    private function clearCache($file = '', $version = '')
    {
        if (is_file($this->updatePath.$this->identifier.'upgrade.lock')) {
            unlink($this->updatePath.$this->identifier.'upgrade.lock');
        }

        if (is_file($file)) {
            unlink($file);
        }

        // 在升级缓存列表里面清除已升级的版本信息
        if ($version) {
            $versionCache = cache($this->cacheUpgradeList);
            unset($versionCache['data'][$version]);
            cache($this->cacheUpgradeList, $versionCache, 3600);
        }

        // 删除升级解压文件
        if (is_dir($this->updatePath)) {
            Dir::delDir($this->updatePath);
        }

        // 删除系统缓存
        Dir::delDir(Env::get('runtime_path').'cache');
        Dir::delDir(Env::get('runtime_path').'temp');
    }
}
