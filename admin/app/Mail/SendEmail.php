<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $author_name;
    public $service_name;
    public $subject;
    public $order_number;
    public $current_timestamp;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($author_name, $service_name, $subject, $order_number, $current_timestamp, $user_name, $user_email, $user_phone)
    {
        $this->author_name = $author_name;
        $this->service_name = $service_name;
        $this->subject = $subject;
        $this->order_number = $order_number;
        $this->current_timestamp = $current_timestamp;
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        $this->user_phone = $user_phone;        
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
            ->markdown('emails.send');
    }
}
