<?php


/**
 * Class GradeEntry
 * stores all grade entries in a session-variable
 * @author Sonja Lechner
 *
 */
class GradeEntry
{
    private $name = "";
    private $email = "";
    private $examDate = "";
    private $subject = "";
    private $grade = "";

    private $errors = [];

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * load all stored grades
     * @return array
     */
    public static function getAll()
    {
        $grades = [];
        if (isset($_SESSION['grades'])) {
            foreach ($_SESSION['grades'] as $grade) {
                $grades[] = unserialize($grade);    // convert string to object
            }
        }
        return $grades;
    }

    /**
     * @return void
     */
    public static function deleteAll()
    {
        if (isset($_SESSION['grades'])) {
            unset($_SESSION['grades']);             //$session_destroy();
        }
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $s = serialize($this);
            $_SESSION['grades'][] = $s;
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getExamDate()
    {
        return $this->examDate;
    }

    /**
     * @param string $examDate
     */
    public function setExamDate($examDate)
    {
        $this->examDate = $examDate;
        return $this;
    }

    /**
     * @param $examDate
     * @return mixed
     */
    public function getExamDateFormatted()
    {
        return date_format(date_create($this->examDate), "d.m.Y");
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @return string|null
     */
    public function getSubjectFormatted(){
        switch($this->subject){
            case 'm':
                return 'Mathematik';
            case 'd':
                return 'Deutsch';
            case 'e':
                return 'Englisch';
            default:
                return null;
        }
    }

    /**
     * @param string $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function hasErrors($field)
    {
        return isset($this->errors[$field]);
    }

    /**
     * @param $name
     * @return bool
     */
    private function validateName()
    {
        if (strlen($this->name) == 0) {
            $this->errors['name'] = "Name darf nicht leer sein";
            return false;
        } else if (strlen($this->name) > 20) {
            $this->errors['name'] = "Name zu lang";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $examDate
     * @return bool
     */
    private function validateExamDate()
    {
        // ungültig wenn: leeres Datum, falsches Format oder in der Zukunft
        try {
            if ($this->examDate == "") {
                $this->errors['examDate'] = "Prüfungsdatum darf nicht leer sein";
                return false;
            } else if (new DateTime($this->examDate) > new DateTime()) {
                $this->errors['examDate'] = "Prüfungsdatum darf nicht in der Zukunft liegen";
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            $this->errors['examDate'] = "Prüfungsdatum ungültig";
            return false;
        }
    }

    /**
     * @param $subject
     * @return bool
     */
    private function validateSubject()
    {
        // nur "m", "d" oder "e" erlaubt
        if ($this->subject != 'm' && $this->subject != 'd' && $this->subject != 'e') {
            $this->errors['subject'] = "Bitte wählen Sie ein Fach aus";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param $grade
     * @return bool
     */
    private function validateGrade()
    {
        // Zahl, 1 bis 5
        if (!is_numeric($this->grade) || $this->grade < 1 || $this->grade > 5) {
            $this->errors['grade'] = "Note ungültig";
            return false;
        } else {
            return true;
        }
    }

    /**
     * validierung der optionalen E-Mail-Adresse
     *
     * @param $email
     * @return bool
     */
    private function validateEmail()
    {
        if ($this->email != "" && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "E-Mail ungültig";
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return int|string
     */
    public function validate()
    {
        return $this->validateName() & $this->validateEmail() & $this->validateExamDate() &
            $this->validateSubject() & $this->validateGrade();
    }

}