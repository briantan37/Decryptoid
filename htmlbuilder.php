<?php
    function buildHTML($type, $message) {
        $metaTag = ($type !== "slogin" ? "" : '<meta http-equiv="refresh" content="3;url=index.php"/>');
        $styleTag = ($type !== "slogin" ? '<style>body {background-image: url("https://i.pinimg.com/originals/77/9e/9a/779e9af714eca52f9daa64fbda14480b.jpg");background-size: cover;background-repeat: no-repeat;}</style>' : "");
        echo <<<_HEAD
            <!DOCTYPE HTML>
            <html>
                <head>
                    <title>Login</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
                    {$metaTag}
                    {$styleTag}
                </head>
                <body>        
_HEAD;

        printType($type, $message);

        echo <<<_FOOT
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        </body>  
    </html>
_FOOT;
    }

    function printType($type, $message) {
        switch($type) {
            case "login":
                echo <<<_LOGIN
                    <div class="container">
                        <h1 class="display-3 text-center text-white">Decryptoid</h1>
                        <div class="w-50 mx-auto">
                            <form method='post' action='login.php' enctype='multipart/form-data'>
                                <div class="form-group">
                                    <label class="text-white" for="username1">Username</label>
                                    <input type="text" class="form-control" name="un" id="username1" placeholder="Enter Username">
                                </div>
                                <div class="form-group">
                                    <label class="text-white" for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name="pw" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <button type="submit" name="login" class="btn btn-success">Login</button>
                                <button type="submit" name="createView" class="btn btn-info">Signup</button>
                            </form>
                        </div>
                    </div>
_LOGIN;
                break;
            case "elogin":
                echo <<<_ERRORLOGIN
                    <div class="alert alert-danger text-center" role="alert">
                        {$message}
                    </div>
_ERRORLOGIN;
                printType("login", null);
                break;
            case "slogin":
                echo <<<_SUCCESSLOGIN
                <h5 class="display-5 text-center">{$message}</h5>
                <h6 class="display-6 text-center">Redirecting in 3 seconds... or <span><a href=index.php>Click here to continue</a></span></h6>
_SUCCESSLOGIN;
                break;
            case "create":
                echo <<<_CREATE
                    <div class="container">
                        <h1 class="display-3 text-center text-white">Decryptoid</h1>
                        <div class="w-50 mx-auto">
                            <form method='post' action='login.php' enctype='multipart/form-data'>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col">
                                            <label class="text-white" for="firstname1">First Name</label>
                                            <input type="text" class="form-control" name="fn" id="firstname1" placeholder="Enter First Name">
                                        </div>
                                        <div class="col">
                                            <label class="text-white" for="lastname1">Last Name</label>
                                            <input type="text" class="form-control" name="ln" id="lastname1" placeholder="Enter Last Name">
                                        </div>
                                    </div>        
                                </div>
                                <div class="form-group">
                                    <label class="text-white" for="username1">Username</label>
                                    <input type="text" class="form-control" name="un" id="username1" placeholder="Enter Username">
                                </div>
                                <div class="form-group">
                                    <label class="text-white" for="exampleInputEmail1">Email address</label>
                                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Enter email">
                                </div>
                                <div class="form-group">
                                    <label class="text-white" for="exampleInputPassword1">Password</label>
                                    <input type="password" class="form-control" name="pw" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label class="text-white" for="exampleInputPassword2">Confirm Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword2" aria-describedby="emailHelp" placeholder="Password">
                                    <small id="emailHelp" class="form-text text-white-50">We'll never share your email and password with anyone else.</small>
                                </div>
                                <button type="submit" name="create" class="btn btn-success">Create</button>
                                <button type="submit" name="loginView" class="btn btn-info">Login</button>
                            </form>
                        </div>
                    </div>
_CREATE;
                break;
            case "ecreate":
                echo <<<_ERRORCREATE
                    <div class="alert alert-danger text-center" role="alert">
                        {$message}
                    </div>
_ERRORCREATE;
                printType("create", null);
                break;

            case "screate":
                echo <<<_SUCCESSCREATE
                    <div class="alert alert-success text-center" role="alert">
                        {$message}
                    </div>
_SUCCESSCREATE;
                printType("login", null);
                break;
            default:

        }
    }
?>