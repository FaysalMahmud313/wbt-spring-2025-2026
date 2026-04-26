<?php

$fnameErr = $lnameErr = $contactErr = $passwordErr =$emailErr = "";
$fname = $lname = $contact = $email = $password = "";


//removes unnecessary whitespace and special charecter
function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}


//indentation after this if must be maintained otherwise form validation will not work properly
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if (empty($_POST["first_name"]))
    {
        $fnameErr = "This name field must be filled up";
    }
    else
    {
        $fname = cleanInput($_POST["first_name"]);

        //checks for special charecter
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname))
        {
            $fnameErr = "Only letters and white space allowed";
        }
    }


    if (empty($_POST["last_name"]))
    {
        $lnameErr = "This name field must be filled up";
    }
    else
    {
        $lname = cleanInput($_POST["last_name"]);

        //checks for special charecter
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname))
        {
            $lnameErr = "Only letters and white space allowed";
        }
    }


    if (empty($_POST["contact"]))
    {
        $contactErr = "This contact field must be filled up";
    }
    else
    {
        $contact = cleanInput($_POST["contact"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }



    if (empty($_POST["password"]))
    {
        $passwordErr = "This password field must be filled up";
    }
    elseif(strlen($_POST["password"])<=8){
        $passwordErr= "The password must have atleast 8 charechters";
    }
    else
    {
        $password = cleanInput($_POST["password"]);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Signup</title>
</head>

<body>

    <h1>Signup as new user!</h1>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <fieldset>

            <legend>Personal information</legend>
            <!--Gives the field a title-->

            <table>

                <tr>
                    <!--one row-->

                    <td>
                        <label for="first_name">First name:</label>
                    </td>
                    <!--column-->

                    <!--when we click the label this ensure that the textbox is selected-->

                    <td>
                        <input type="text" id="first_name" name="first_name" value="<?= $fname ?>">
                    </td>
                    <!--id used for frontend stuff, name used in forms to pass data to php, value is used to put php variable data in textbox-->

                    <td>
                        <span style="color:red;">
                            <?= $fnameErr ?>
                        </span>
                    </td>

                </tr>


                <tr>

                    <td>
                        <label for="last_name">Last name:</label>
                    </td>
                    <!--one column for label, used php in value-->

                    <td>
                        <input type="text" id="last_name" name="last_name" value="<?= $lname ?>">
                    </td>
                    <!--another one for textbox-->

                    <td>
                        <span style="color:red;">
                            <?= $lnameErr ?>
                        </span>
                    </td>

                </tr>


                <tr>
                    <!--one row-->

                    <td>
                        <label for="contact">Contact:</label>
                    </td>
                    <!--column-->

                    <!--when we click the label this ensure that the textbox is selected-->

                    <td>
                        <input type="text" id="contact" name="contact" value="<?= $contact ?>">
                    </td>
                    <!--id used for frontend stuff, name used in forms to pass data to php, value is used to put php variable data in textbox-->

                    <td>
                        <span style="color:red;">
                            <?= $contactErr ?>
                        </span>
                    </td>

                </tr>
                <tr>
                    <td>Email <span class="required">*</span></td>
                    <td>
                        <input type="text" name="email" value="<?= $email ?>">

                    </td>
                    <td>
                            <span style="color:red;">
                            <?= $emailErr ?>
                        </span>
                    </td>
            </tr>


                <tr>

                    <td>
                        <label for="password">Password:</label>
                    </td>
                    <!--one column for label, used php in value-->

                    <td>
                        <input type="password" id="password" name="password">
                    </td>
                    <!--another one for textbox-->

                    <td>
                        <span style="color:red;">
                            <?= $passwordErr ?>
                        </span>
                    </td>

                </tr>


                <tr>
                    <td>
                        <input type="submit" value="Register">
                    </td>
                </tr>

            </table>

        </fieldset>

    </form>

</body>

</html>