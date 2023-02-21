<?php

    namespace App\Http\Controllers\Contact;

    use App\Http\Controllers\Controller;
    use App\Mail\ContactForm;
    use App\Models\Contact;
    use App\Notifications\ContactFormNotification;
    use App\Notifications\WelcomeEmailNotification;
    use Carbon\Carbon;
    use http\Env;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Facades\Storage;

    class ContactController extends Controller
    {
        public function createContact(Request $request)
        {
            $validatedData = $request->validate([
                'name' => 'required|string|unique:users,name',
                'email' => 'required|string|unique:users,email',
                'attachment' => 'mimes:png,csv,svg',
                'message' => 'required|string:255'
            ], [
                'name.required' => 'Please enter name.',
                'email.unique' => 'That email address is already in use.',
                'attachment.mimes' => 'The file must be a PNG, CSV, or SVG.',
                'message.required' => 'Message is required'
            ]);

            if ($validatedData) {
                if ($request->hasFile('attachment')) {

                    // Process the uploaded file
                    $file = $request->file('attachment');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads/contact_att'), $filename);

                    //Create a record into the database
                    $data = Contact::create([
                        'name' => $validatedData['name'],
                        'email' => $validatedData['email'],
                        'attachment' => $filename,
                        'message' => $validatedData['message']
                    ]);

                    //Send a mail
                    Mail::to(\env('MAIL_TO_ADDRESS'))->send(new ContactForm($validatedData));

                    //Send a response
                    return response()->json($data, Response::HTTP_OK);
                }

                //Create a record into the database
                $data = Contact::create([
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'attachment' => null,
                    'message' => $validatedData['message']
                ]);

                //Send a mail
                Mail::to(\env('MAIL_TO_ADDRESS'))->send(new ContactForm($validatedData));

                //Send a response
                return response()->json($data, Response::HTTP_OK);
            }

            //Send a response
            return response()->json('Error in creating Contact', Response::HTTP_CONFLICT);
        }

    }
