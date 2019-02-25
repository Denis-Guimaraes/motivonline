<?php
namespace MotivOnline\Controller;

use MotivOnline\Model\UserModel;
use MotivOnline\Util\User;

class UserController extends CoreController
{
    private $data;
    private $templateName;

    public function signup()
    {
        $errorList = [];
        // Check and set parameters
        if (!empty($_POST)) {
            $firstname = (isset($_POST['firstname'])) ? $_POST['firstname'] : '';
            $lastname = (isset($_POST['lastname'])) ? $_POST['lastname'] : '';
            $email = (isset($_POST['email'])) ? $_POST['email'] : '';
            $password= (isset($_POST['password'])) ? $_POST['password'] : '';
            $confirmPassword = (isset($_POST['confirmPassword'])) ? $_POST['confirmPassword'] : '';
            $picture = (isset($_POST['picture'])) ? $_POST['picture'] : '';
            $phoneNumber = (isset($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : '';
            $zipCode = (isset($_POST['zipCode'])) ? $_POST['zipCode'] : '';
            $city = (isset($_POST['city'])) ? $_POST['city'] : '';
            $adress = (isset($_POST['adress'])) ? $_POST['adress'] : '';

            if (empty($email)) {
                $errorList[] = 'Email vide';
            } elseif (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $errorList[] = 'Email incorrect';
            }
            if (empty($password)) {
                $errorList[] = 'Mot de passe vide';
            } elseif( strlen($password) < 8) {
                $errorList[] = 'Mot de passe trop court, minimum 8 caractères';
            }
            if ($password !== $confirmPassword) {
                $errorList[] = 'Les deux mots de passe sont différents';
            }
            
            if (empty($errorList)) {
                // Check if user already exist
                $userModel = new UserModel();
                $user = $userModel->findByEmail($email);
                if ($userModel) {
                    $errorList[] = 'Le compte existe déjà pour cet email';
                } else {
                    // Set user and insert it in database
                    $encryptedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $userModel->setFirstname($firstname);
                    $userModel->setLastname($lastname);
                    $userModel->setEmail($email);
                    $userModel->setPassword($encryptedPassword);
                    $userModel->setPicture($picture);
                    $userModel->setPhone_number($phoneNumber);
                    $userModel->setZip_code($zipCode);
                    $userModel->setCity($city);
                    $userModel->setAdress($adress);
                    $insert = $userModel->insert();

                    if ($insert) {
                        // Set success
                        $this->data['success'] = 'Vous pouvez maintenant vous connecter et profiter de notre service.';
                    } else {
                        $errorList[] = 'Une erreur inattendue s\'est produite';
                    }
                }
            }
        }
        // Set data and return signup view
        $this->templateName = 'user/signup';
        $this->data['error'] = $errorList;
        $this->show($this->templateName, $this->data);
    }

    public function signin()
    {
        $errorList = [];
        if (!empty($_POST)) {
            // Check and set parameters
            $email = (isset($_POST['email'])) ? $_POST['email'] : '';
            $password = (isset($_POST['password'])) ? $_POST['password'] : '';

            // Check if user exist and if password match
            $userModel = new UserModel();
            $user = $userModel->findByEmail($email);
            if (!empty($user)) {
                $passwordInBdd = $user->getPassword();
                if (password_verify($password ,$passwordInBdd)) {
                    // Connect user in session
                    User::connect($user);
                    // Redirect to profile
                    header('Location: '. $this->getRouter()->generate('user_profile'));
                } else {
                    $errorList[]= "L'identifiant ou le mot de passe est incorrecte";
                }
            } else {
                $errorList[]= "L'identifiant ou le mot de passe est incorrecte";
            }
        }
        // Set data and return sigin view
        $this->templateName = 'user/signin';
        $this->data['error'] = $errorList;
        $this->show($this->templateName, $this->data);
    }

    public function profile()
    {
        if (!User::isConnected()) {
            header('Location: '. $this->getRouter()->generate('main_home'));
        }
        $this->templateName = 'user/profile';
        $this->show($this->templateName);
    }

    public function updateUser()
    {
        $errorList= [];
        if (!User::isConnected()) {
            header('Location: '. $this->getRouter()->generate('main_home'));
        }
        // Check and set parameters
        if (!empty($_POST)) {
            $firstname = (isset($_POST['firstname'])) ? $_POST['firstname'] : '';
            $lastname = (isset($_POST['lastname'])) ? $_POST['lastname'] : '';
            $picture = (isset($_POST['picture'])) ? $_POST['picture'] : '';
            $phoneNumber = (isset($_POST['phoneNumber'])) ? $_POST['phoneNumber'] : '';
            $zipCode = (isset($_POST['zipCode'])) ? $_POST['zipCode'] : '';
            $city = (isset($_POST['city'])) ? $_POST['city'] : '';
            $adress = (isset($_POST['adress'])) ? $_POST['adress'] : '';

            // Set user and update it in database
            $user = User::getConnectedUser();
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setPicture($picture);
            $user->setPhone_number($phoneNumber);
            $user->setZip_code($zipCode);
            $user->setCity($city);
            $user->setAdress($adress);
            $userUpdate = $user->update();
            
            if ($userUpdate) {
                // Set success
                $this->data['success'] = 'Vos informations personnelles ont bien été modifiées.';
            } else {
                $errorList[] = 'Une erreur inattendue s\'est produite';
            }
        }
        // Set data and return profile view
        $this->templateName = 'user/profile';
        $this->data['error'] = $errorList;
        $this->show($this->templateName, $this->data);
    }

    public function signout()
    {
        User::disconnect();
        header('Location: '. $this->getRouter()->generate('main_home'));

    }

    public function deleteUser()
    {
        if (!User::isConnected()) {
            header('Location: '. $this->getRouter()->generate('main_home'));
        }
        $user = User::getConnectedUser();
        $userDelete = $user->delete();
        User::disconnect();
        header('Location: '. $this->getRouter()->generate('main_home'));

    }

    // Getters and Setters
    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;

        return $this;
    }
}