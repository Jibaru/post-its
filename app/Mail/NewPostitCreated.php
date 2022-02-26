<?php

namespace App\Mail;

use App\Models\Group;
use App\Models\Postit;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewPostitCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Group $group;
    public Postit $postit;
    public User $userCreator;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($group, $postit, $userCreator)
    {
        $this->group = $group;
        $this->postit = $postit;
        $this->userCreator = $userCreator;

        $this->subject('Nuevo Postit Creado');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.postit_notification');
    }
}
