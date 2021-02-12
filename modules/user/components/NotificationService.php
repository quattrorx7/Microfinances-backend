<?php

namespace app\modules\user\components;

use app\models\User;
use Exception;
use ExponentPhpSDK\Exceptions\ExpoRegistrarException;

class NotificationService 
{

    private $expo;
    private $users;

    public function __construct()
    {
        // You can quickly bootup an expo instance
        $this->expo = \ExponentPhpSDK\Expo::normalSetup();
    }

    public function sendToAdmin(string $body, string $title='', array $data = null){
        $userRepository = new UserRepository();
        $users = $userRepository->getAdminForNotification();
        $this->users = $users;
        if(count($users)==0) return false;

        $userIds = array_map(function($user){
            return $user->id;
        }, $users);
        
        try{
            $this->send($userIds, $body, $title, $data);
        }catch(ExpoRegistrarException $ex){
            foreach($this->users as $user){
                $this->subscribe($user->id, $user->token);
            }
            $this->send($userIds, $body, $title, $data);
        }
    }

    public function sendToUser(User $user, string $body, string $title='', array $data = null){
        if(!$user->isNotification()) return false;

        try{
            $this->send([$user->id], $body, $title, $data);
        }catch(ExpoRegistrarException $ex){
            $this->subscribe($user->id, $user->token);
            $this->send([$user->id], $body, $title, $data);
        }
    }

    public function send(array $userIds, string $body, string $title='', array $data = null){
        // Build the notification data
        $notification = ['body' => $body, 'title'=>$title, 'sound'=>'default'];

        if($data) 
            $notification['data'] = json_encode($data);

        $channelNames = [];
        foreach($userIds as $id){
            $channelNames[] = $this->getNormalChannelName($id);
        }

        try{
            // Notify an interest with a notification
            $this->expo->notify($channelNames, $notification);
        }catch(ExpoRegistrarException $ex){
            throw $ex;
        }
        catch(Exception $ex){
        }
    }

    public function subscribe($userId, $token){
        // Subscribe the recipient to the server
        $this->expo->subscribe($this->getNormalChannelName($userId), $this->getNormalToken($token));
    }

    public function unsubscribe($userId, $token){
        // Subscribe the recipient to the server
        $this->expo->unsubscribe($this->getNormalChannelName($userId));
    }

    public function getNormalChannelName($userId){
        return 'user_'.$userId;
    }

    public function getNormalToken($token){
        return $token;
    }

}