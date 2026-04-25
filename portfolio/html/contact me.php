<?php
$firstNameErr = $emailErr = $genderErr = $lastNameErr = $rocErr = $topicErr = $companyErr = "";
$firstName = $email = $gender = $lastName = $roc = $topic = $company = "";

function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["firstName"])) {
        $firstNameErr = "First Name is required";
    } else {
        $firstName = cleanInput($_POST["firstName"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $firstName)) {
            $firstNameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["lastName"])) {
        $lastNameErr = "Last Name is required";
    } else {
        $lastName = cleanInput($_POST["lastName"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lastName)) {
            $lastNameErr = "Only letters and white space allowed";
        }
    }

    // Email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["company"])) {
        $companyErr = "Company Name is required";
    } else {
        $company = cleanInput($_POST["company"]);
    }

    if (empty($_POST["topic"])) {
        $topicErr = "Topic must be selected!";
    } else {
        $topic = cleanInput($_POST["topic"]);
    }

    if (empty($_POST["roc"])) {
        $rocErr = "Reason must be selected!";
    } else {
        $roc = $_POST["roc"];
    }

    // Gender
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = cleanInput($_POST["gender"]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>

    <link rel="stylesheet" href="..\css\contact.css">

</head>

<body>


    <nav>
        <ul>
            <li><a href="index.html">Home</a></li>
            <li><a href="education.html">Education</a></li>
            <li><a href="experience.html">Experience</a></li>
            <li><a href="project.html">Projects</a></li>
            <li><a href="contact me.php" class="active">Contact Me</a></li>
        </ul>
    </nav>

    <h1>Contact Me</h1>

     
    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    <fieldset>
        <legend>Contact Me Form</legend>

        <table>

            <tr>
                <td><label for="firstName">First Name:</label></td>
                <td>
                    <input type="text" id="firstName" name="firstName" value="<?= $firstName ?>">
                    <span style="color:red"><?= $firstNameErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label for="lastName">Last Name:</label></td>
                <td>
                    <input type="text" id="lastName" name="lastName" value="<?= $lastName ?>">
                    <span style="color:red"><?= $lastNameErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label for="email">Email:</label></td>
                <td>
                    <input type="email" id="email" name="email" value="<?= $email ?>">
                    <span style="color:red"><?= $emailErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label>Gender:</label></td>
                <td>
                    <input type="radio" name="gender" value="female" <?= ($gender == "female") ? "checked" : "" ?>> Female
                    <input type="radio" name="gender" value="male" <?= ($gender == "male") ? "checked" : "" ?>> Male
                    <span style="color:red"><?= $genderErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label>Reason of Contact:</label></td>
                <td>
                    <input type="checkbox" name="roc[]" value="project"> Project
                    <input type="checkbox" name="roc[]" value="thesis"> Thesis
                    <input type="checkbox" name="roc[]" value="job"> Job
                    <span style="color:red"><?= $rocErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label for="topic">Topic:</label></td>
                <td>
                    <select id="topic" name="topic">
                        <option value="">Select a topic</option>
                        <option value="Web Development" <?= ($topic=="Web Development")?"selected":"" ?>>Web Development</option>
                        <option value="Mobile App Development" <?= ($topic=="Mobile App Development")?"selected":"" ?>>Mobile App Development</option>
                        <option value="AI/ML Development" <?= ($topic=="AI/ML Development")?"selected":"" ?>>AI/ML Development</option>
                    </select>
                    <span style="color:red"><?= $topicErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label for="company">Company:</label></td>
                <td>
                    <input type="text" id="company" name="company" value="<?= $company ?>">
                    <span style="color:red"><?= $companyErr ?></span>
                </td>
            </tr>

            <tr>
                <td><label for="consultationDate">Consultation Date:</label></td>
                <td>
                    <input type="date" id="consultationDate" name="consultationDate">
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <button type="submit">Submit Form</button>
                    <button type="reset">Reset Form</button>
                </td>
            </tr>

        </table>

    </fieldset>



</form>

<?php if (
    $_SERVER["REQUEST_METHOD"] == "POST" &&
    !$firstNameErr && !$lastNameErr && !$emailErr &&
    !$genderErr && !$topicErr && !$rocErr && !$companyErr
): ?>
    
    <h3>Submitted Values</h3>

    First Name: <?= $firstName ?><br>
    Last Name: <?= $lastName ?><br>
    Email: <?= $email ?><br>
    Company: <?= $company ?><br>
    Topic: <?= $topic ?><br>

    Reason of Contact:
    <?php 
        if (!empty($roc)) {
            echo implode(", ", $roc);
        }
    ?><br>

    Gender: <?= $gender ?><br>

<?php endif; ?>

</body>

</html>