<?php

use Uniform\Form;

return function ($kirby)
{
    $form = new Form([
        'name' => [
            'rules' => ['required'],
            'message' => 'Please enter your first name',
        ],
        'lastname' => [
            'rules' => ['required'],
            'message' => 'Please enter your last name',
        ],
        'email' => [
            'rules' => ['required', 'email'],
            'message' => 'Please enter a valid email address',
        ],
        'phone' => [
            'rules' => ['required', 'tel'],
            'message' => 'Please enter a valid phone number',
        ],
        'service' => [
            'rules' => ['required'],
            'message' => 'Please choose a service',
        ],
        'message' => [
            'rules' => ['required'],
            'message' => 'Please enter a message',
        ],
    ]);

    if ($kirby->request()->is('POST')) {
        $form->emailAction([
            'to' =>  $kirby->site()->email()->value(),
            'from'    => $kirby->site()->emailFrom()->value(),
            'subject' => 'New lead on '.$kirby->site()->title(),
            'template' => 'lead',
        ])->done();
    };

    return compact('form');
};