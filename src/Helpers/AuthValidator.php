<?php


namespace App\Helpers;


use App\Helpers\Interfaces\ValidatorInterface;
use App\Models\User;

class AuthValidator extends Validator implements ValidatorInterface
{
    /**
     * Validate user input before login
     * @param $username
     * @param $password
     * @throws \Exception
     */
    public function validateUserLogin($username, $password)
    {
        if(isset($_SESSION['id'])){
            header('Location: index.php');
        }

        $this->validateRequired($username, 'Username');
        $this->validateRequired($password, 'Password');

        if(!empty($this->getErrors())){

            header('Location: login-index.php?errors=' . urlencode(json_encode($this->getErrors())) );
            exit();
        }

        $this->validateExisting('users', 'username', $username, 'User');

        if(!empty($this->getErrors())){
            header('Location: login-index.php?errors=' . urlencode(json_encode($this->getErrors())) );
            exit();
        }

        $this->validateUserPassword($username, $password);

        if(!empty($this->getErrors())){
            header('Location: login-index.php?errors=' . urlencode(json_encode($this->getErrors())) );
            exit();
        }
    }

    /**
     * Validate user input before register
     * @param $email
     * @param $username
     * @param $password
     * @param $name
     */
    public function validateUserRegister($email, $username, $password, $name)
    {
        $this->validateRequired($username, 'Username');
        $this->validateRequired($password, 'Password');
        $this->validateRequired($email, 'Email');
        $this->validateRequired($name, 'Name');
        if(!empty($this->getErrors())){

            header('Location: register-index.php?errors=' . urlencode(json_encode($this->getErrors())) );
            exit();
        }

        $this->validate('email', $email, null);
        $this->validate('password', $password, null);
        $this->validateUnique('users', 'email', $email, 'email');
        $this->validateUnique('users', 'username', $username, 'username');
        if(!empty($this->getErrors())){

            header('Location: register-index.php?errors=' . urlencode(json_encode($this->getErrors())) );
            exit();
        }
    }

    /**
     * Validate User Password
     * @param mixed $userName
     * @param mixed $password
     * @throws \Exception
     */
    public function validateUserPassword($userName, $password)
    {
        $model = new User();
        $user = $model->getUserByUserName($userName);

        if(!password_verify($password, $user->password)){
            $this->errors[] = 'Incorrect Password';
        }
    }


}