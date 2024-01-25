<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {

    use HasApiTokens,
        HasFactory,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    public function name(){
    return $this->order_label."-".($this->gender == 'male' ? 'Mr':'Mrs').". ".$this->name;
      
        
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',
        'qr_code','gender' ,
        'status', 'order_label'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendNotification($type, $target, $message, $object) {
        if ($this->fcm_token) {
            $firebaseToken = [$this->fcm_token];

            $SERVER_API_KEY = "";

            $body = new \stdClass();
            $body->type = $type;
            $body->target = $target;
            $body->message = $message;
            $body->object = $object;

            $data = [
                 "content-available" => 0 ,
                "aps" => [
                    "content-available" => 0
                ],
                "registration_ids" => $firebaseToken,
                "notification" => [
                     "content-available" => 0 ,
                    "title" => "Message from Ejudge",
                    "body" => $message,
                     "aps" => [
                    "content-available" => 0
                ],
                ],
                "data" => $body
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);

            return $body;
        } else {
            return "FCM token was not updated or null for user " . $this->id . " [" . $this->fcm_token . "]";
        }
    }

}
