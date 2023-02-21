<?php

    namespace App\Mail;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;

    class ContactForm extends Mailable
    {
        use Queueable, SerializesModels;

        /**
         * Create a new message instance.
         *
         * @return void
         */
        public $data;

//    public $attachment;

        public function __construct($data)
        {
            $this->data = $data;
        }

        /**
         * Build the message.
         *
         * @return $this
         */
        public function build()
        {
            if (isset($this->data['attachment'])&&$this->data['attachment']!==null) {
                return $this->view('emails')
                    ->subject('New Contact Form Submission')
                    ->attach($this->data['attachment'], [
                        'as' => $this->data['attachment'],
                        'mime' => 'png,csv,svg'
                    ]);
            }
            return $this->view('emails')
                ->subject('New Contact Form Submission');
//            ->with([
//                'name' => $this->data['name'],
//                'email' => $this->data['email'],
//                'message' => $this->data['message'],
//            ]);
        }
    }
