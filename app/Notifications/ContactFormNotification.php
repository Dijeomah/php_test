<?php

    namespace App\Notifications;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Messages\MailMessage;
    use Illuminate\Notifications\Notification;

    class ContactFormNotification extends Notification
    {
        use Queueable;

        public $contact;

        /**
         * Create a new notification instance.
         *
         * @return void
         */
        public function __construct($contact)
        {
            //
            $this->contact = $contact;
        }

        /**
         * Get the notification's delivery channels.
         *
         * @param mixed $notifiable
         * @return array
         */
        public function via($notifiable)
        {
            return ['mail'];
        }

        /**
         * Get the mail representation of the notification.
         *
         * @param mixed $notifiable
         * @return \Illuminate\Notifications\Messages\MailMessage
         */
        public function toMail($notifiable)
        {
            return (new MailMessage)
                ->greeting('Hello, ')
                ->line('The introduction to the notification.')
                ->line('Name: ' . $this->contact->name)
                ->line('Email: ' . $this->contact->email)
                ->line('Message: ' . $this->contact->message)
                ->attach(public_path('uploads/contact_att' . $this->contact->attachment))
                ->line('Thank you for using our application!');
        }

        /**
         * Get the array representation of the notification.
         *
         * @param mixed $notifiable
         * @return array
         */
        public function toArray($notifiable)
        {
            return [
                //
            ];
        }
    }
