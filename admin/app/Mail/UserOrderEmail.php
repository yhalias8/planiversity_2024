<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserOrderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $author_name;
    public $service_name;
    public $subject;
    public $order_number;
    public $username;
    public $current_timestamp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($author_name, $service_name, $subject, $order_number, $username, $current_timestamp)
    {
        $this->author_name = $author_name;
        $this->service_name = $service_name;
        $this->subject = $subject;
        $this->order_number = $order_number;
        $this->username = $username;
        $this->current_timestamp = $current_timestamp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('plans@planiversity.com', 'Planiversity')
            ->subject($this->subject)
            ->markdown('emails.user');
    }
}
