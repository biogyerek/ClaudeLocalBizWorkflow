<?php

use Uniform\Form;

return function ($kirby)
{
    $form = new Form([
        'name' => [
            'rules' => ['required'],
            'message' => 'Kérjük, adja meg keresztnevét',
        ],
        'lastname' => [
            'rules' => ['required'],
            'message' => 'Kérjük, adja meg vezetéknevét',
        ],
        'email' => [
            'rules' => ['required', 'email'],
            'message' => 'Kérjük, adjon meg egy érvényes e-mail címet',
        ],
        'phone' => [
            'rules' => ['required', 'tel'],
            'message' => 'Kérjük, adjon meg egy érvényes telefonszámot',
        ],
        'message' => [
            'rules' => ['required'],
            'message' => 'Kérjük, írjon üzenetet',
        ],
    ]);

    if ($kirby->request()->is('POST')) {
        $form->emailAction([
            'to' =>  $kirby->site()->email()->value(),
            'from'    => $kirby->site()->emailFrom()->value(),
            'subject' => 'Új űrlap beküldés: '.$kirby->site()->title(),
            'template' => 'contact',
        ])->done();
    };

    return compact('form');
};