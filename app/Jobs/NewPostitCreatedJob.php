<?php

namespace App\Jobs;

use App\Mail\NewPostitCreated;
use App\Models\Group;
use App\Models\Postit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NewPostitCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Group $group;
    private Postit $postit;
    private User $userCreator;
    private array $emails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($group, $postit, $userCreator, $emails)
    {
        $this->group = $group;
        $this->postit = $postit;
        $this->userCreator = $userCreator;
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->emails)
            ->send(new NewPostitCreated(
                $this->group,
                $this->postit, 
                $this->userCreator
            ));
    }
}
