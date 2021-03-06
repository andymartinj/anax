<?php

namespace Anax\User\HTMLForm;

use \Anax\HTMLForm\FormModel;
use \Anax\DI\DIInterface;
use \Anax\User\User;

/**
 * Example of FormModel implementation.
 */
class CreateUserForm extends FormModel
{

    /**
     * Constructor injects with DI container.
     *
     * @param Anax\DI\DIInterface $di a service container
     */
    public function __construct(DIInterface $di)
    {
        parent::__construct($di);
        $this->form->create(
            [
                "class" => "standard-form",
                "legend" => "Registrera",
            ],
            [
                "acronym" => [
                    "type"        => "text",
                    "placeholder" => "Användarnamn",
                    "validation" => ["not_empty"],
                    "class" => "standard-input",
                    "label" => "",
                ],

                "email" => [
                    "type"        => "email",
                    "placeholder" => "Mejl",
                    "validation" => ["not_empty"],
                    "class" => "standard-input",
                    "label" => "",
                ],

                "password" => [
                    "type"        => "password",
                    "placeholder" => "Lösenord",
                    "validation" => ["not_empty"],
                    "class" => "standard-input",
                    "label" => "",
                ],

                "password-again" => [
                    "type"        => "password",
                    "placeholder" => "Lösenord igen",
                    "validation" => [
                        "match" => "password"
                    ],
                    "class" => "standard-input",
                    "label" => "",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Skapa användare",
                    "callback" => [$this, "callbackSubmit"],
                    "class" => "standard-button",
                ],
            ]
        );
    }



    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $acronym       = $this->form->value("acronym");
        $password      = $this->form->value("password");
        $passwordAgain = $this->form->value("password-again");
        $email =         $this->form->value("email");

        // Check password matches
        if ($password !== $passwordAgain) {
            $this->form->rememberValues();
            $this->form->addOutput("Password did not match.");
            return false;
        }

        // Save to database
        // $db = $this->di->get("db");
        // $password = password_hash($password, PASSWORD_DEFAULT);
        // $db->connect()
        //    ->insert("User", ["acronym", "password"])
        //    ->execute([$acronym, $password]);
        $user = new User();
        $user->setDb($this->di->get("db"));
        $user->acronym = $acronym;
        $user->setPassword($password);
        $user->email = $email;
        $user->role = "user";
        $user->save();

        $this->form->addOutput("User was created.");
        return true;
    }
}
