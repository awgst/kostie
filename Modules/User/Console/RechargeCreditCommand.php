<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Modules\User\Jobs\RechargeUserCreditJob;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RechargeCreditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'user:recharge-credit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge User Credit every start of month.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        RechargeUserCreditJob::dispatch()->onQueue('user-recharge');
    }
}
