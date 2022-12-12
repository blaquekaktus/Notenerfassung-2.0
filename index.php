<?php

session_start();
require_once "./models/GradeEntry.php";

$e = new GradeEntry();  // create a new Grade Entry Object $e
$message = '';
// Formularverarbeitung (HTTP POST Request)

if(isset($_POST['submit'])){
    $e->setName(isset($_POST['name']) ? $_POST['name'] : "");
    $e->setEmail(isset($_POST['email']) ? $_POST['email'] : "");
    $e->setExamDate(isset($_POST['examDate']) ? $_POST['examDate'] : "");
    $e->setGrade(isset($_POST['grade']) ? $_POST['grade'] : "");
    $e->setSubject(isset($_POST['subject']) ? $_POST['subject'] : "");

// Datenvalidierung und beim fehlerhaften Eingaben, Fehlermeldung Ausgaben
    if ($e->validate()) {
        $e->save();
        $message = "<p class = 'alert alert-success'> Die eingegebene Daten sind in Ordnung!</p>";
    }else{
         $message ="<div class = 'alert alert-danger'><p>Die eingegebene Daten sind Fehlerhaft</p><ul>";
        foreach ($e->getErrors() as $key => $value){
            $message.="<li>".$value."</li>";
        }
        $message.="</ul></div>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" >

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <title>Notenerfassung 2.0</title>
</head>
<body>

    <div class="container">
        <h1 class = "mt-5 mb-3">Notenerfassung 2.0</h1>
        <?= $message ?>
        <form id="form-grade" action="index.php" method="post">

            <div class="row">

                <div class="col-sm-6 form-group">
                    <label for="name">Name*</label>
                    <input type="text"
                           name="name"
                           class="form-control <?= $e->hasErrors('name') ? 'is-invalid' : '' ?>"
                           value="<?=htmlspecialchars($e->getName()) ?>"
                           maxlength ="20"
                           required ="required"
                    />
                </div>

                <div class="col-sm-6 form-group">
                    <label for="email">E-mail</label>
                    <input type="email"
                           name="email"
                           class="form-control <?= $e->hasErrors('email') ? 'is-invalid' : '' ?>"
                           value="<?=htmlspecialchars($e->getEmail()) ?>"
                    >
                </div>

            </div>

            <div class="row">

                <div class="col-sm-4 form-group">
                    <label for="subject">Fach*</label>
                    <select name="subject"
                           class="custom-select <?= $e->hasErrors('subject') ? 'is-invalid' : '' ?>"
                           required>
                        <option value="" hidden>-Fach auswählen-</option>
                        <option value="m" <?php if($e->getSubject()=='m') echo "selected ='selected'"; ?> >Mathematik</option>
                        <option value="d" <?php if($e->getSubject()=='d') echo "selected ='selected'"; ?> >Deutsch</option>
                        <option value="e" <?php if($e->getSubject()=='e') echo "selected ='selected'"; ?> >Englisch</option>
                    </select>
                </div>

                <div class="col-sm-4 form-group">
                    <label for="grade">Note*</label>
                    <input type="number"
                           name="grade"
                           class="form-control <?= $e->hasErrors('grade') ? 'is-invalid' : '' ?>"
                           value="<?=htmlspecialchars($e->getGrade()) ?>"
                           min="1"
                           max="5"
                           required = "required"
                    />
                </div>

                <div class="col-sm-4 form-group">
                    <label for="examDate">Prüfungsdatum*</label>
                    <input type="date"
                           name="examDate"
                           class="form-control <?= $e->hasErrors('examDate') ? 'is-invalid' : '' ?>"
                           value="<?=htmlspecialchars($e->getExamDate()) ?>"
                           onchange="validateExamDate(this)"
                           required="required"
                    />
                </div>

            </div>

            <div class="row mt-5 mb-3">

                <div class="col-sm-3 mb-3">
                     <input type="submit"
                            name="submit"
                            class="btn btn-primary btn-block"
                            value="validieren"
                     />
                </div>

                <div class="col-sm-3 mb-3">
                    <a href="index.php" class="btn btn-secondary btn-block">Löschen</a>
                </div>

            </div>
        </form>

        <h2 class="mt-5 mb-3">Noten</h2>

        <div id="grades">
            <table class="table">
                <thead>
                <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Prüfungsdatum</th>
                <th>Fach</th>
                <th>Note</th>
                </tr>
                </thead>

                <tbody>
                <?php
                $grades = GradeEntry::getAll();
                foreach ($grades as $grade){
                    echo "<tr>";
                    echo "<td>".$grade->getName()."</td>";
                    echo "<td>".$grade->getEmail()."</td>";
                    echo "<td>".$grade->getExamDateFormatted()."</td>";
                    echo "<td>".$grade->getSubjectFormatted()."</td>";
                    echo "<td>".$grade->getGrade()."</td>";
                    echo "</tr>";
                }
                ?>
                </tbody>

            </table>

        </div>

        <?php
         if(count($grades)>0){
        ?>
             <form action="clear.php" method="post">
                <input type="submit" name="clear" class="btn btn-danger" value="Alle Daten Löschen"/>
             </form>
        <?php
         }
        ?>

    </div>
</body>
</html>

