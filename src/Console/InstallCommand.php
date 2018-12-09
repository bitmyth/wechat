<?php
/**
 * Created by PhpStorm.
 * User: gsh
 * Date: 2018/12/7
 * Time: 9:09 AM
 */

namespace Bitmyth\Wechat\Console;

class InstallCommand extends \Illuminate\Console\Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wechat:install
                            {--force : Overwrite keys they already exist}
                            {--length=4096 : The length of the private key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Wechat for use';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('wechat install');
        $this->callSilent('vendor:publish', ['--tag' => 'wechat-config']);
        $this->callSilent('vendor:publish', ['--tag' => 'wechat-migrations']);
        $this->callSilent('vendor:publish', ['--tag' => 'wechat-assets']);
//        $this->call('passport:keys', ['--force' => $this->option('force'), '--length' => $this->option('length')]);
//        $this->call('passport:client', ['--password' => true, '--name' => config('app.name').' Password Grant Client']);
    }
}
