<?php
include_once 'loader.php';
//SELECT * FROM user JOIN compte_vendeur USING(idCptVend) WHERE nomCptVend='All' AND emailUser='alladmin@gmail.com' AND pwdUser='8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a8' AND etatUser=1 AND etatCptVend=1

$pass = hash('sha256', "admin");
if (isset($_SESSION['loginON'])) {
    header('Location:index.php');
    exit();
}

if (isset($login)) {

    $compte = $comptePHP;
    $email = $emailPHP;
    $password = hash('sha256', $passwordPHP);
    
    $data = Login::userConnexion($compte, $email, $password);
    $count = count($data);

    if ($count != 0) {
        
        $_SESSION['loginON'] = 1;
        $_SESSION['data'] = $data;
        $_SESSION['last_login_timestamp'] = time();
        $id_user = $data[0]['idUser'];
        //insertion
        Connexion::insertConnexion($id_user);
        exit('oui');
    } else {
        exit('non'.$password);
    }
}
//<?php echo realpath('chemin.php');
?>
<!Doctype html>
<html>
    <head>
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
        <script src="library/jquery/jquery-1.9.1.js"></script>
        <link rel="stylesheet" type="text/css" href="library/bootstrap/css/bootstrap.min.css" />
        <script type= "text/javascript" src="library/bootstrap/js/bootstrap.min.js"></script>
        <title>topcosmetics</title>
    </head>
    <body>
        <div id="content">
            <div class="logo"></div>
            <form method="post"  name="loginForm" id="loginFormID" >
                <div class="login-block">
                    <h1>Login</h1>
                    <div class="form-group log-status">
                        <input type="text" name="compte" class="form-control" value="" placeholder="Nom Société" id="compte" />
                    </div>
                    <div class="form-group log-status">
                        <input type="text" name="email" class="form-control" value="" placeholder="Email Utilisateur" id="email" />
                    </div>
                    <div class="form-group log-status">
                        <input type="password" name="password" class="form-control" value="" placeholder="Mot de passe" id="password" />
                    </div>
                    <span class="alert" >Veillez remplir tous les champs. </span>
                    <span class="success" style="border: 1px solid grey; margin: 5px 0px; padding: 20px 50px;" >Connexion avec success.</span>
                    <span class="error">Pseudo ou Mot de passe incorrect.</span>

                    <button type="button" class="log-btn">Connexion</button>
                </div> 
            </form>
        </div>
        <script>
            $(document).ready(function () {

                $('.log-btn').click(function () {
                    var email = $("#email").val();
                    var compte = $("#compte").val();
                    var password = $("#password").val();
                    
                    console.log('Email'+email);
                    console.log('Compte'+compte);
                    console.log('Compte'+password);
                    
                    if (compte == "" || email == "" || password == "") {
                        $('.log-status').addClass('wrong-entry');
                        $('.alert').fadeIn(500);
                        setTimeout("$('.alert').fadeOut(1500);", 3000);
                    } else {
                        $.ajax(
                                {
                                    url: 'login.php',
                                    method: 'POST',
                                    data: {
                                        login: 1,
                                        comptePHP: compte,
                                        emailPHP: email,
                                        passwordPHP: password
                                    },
                                    success: function (reponse) {
                                        if (reponse == "oui") {
                                            $('.success').fadeIn(500);
                                            setTimeout("$('.success').fadeOut(1500);", 3000);
                                            window.location = 'index.php';
                                            
                                        } else {
                                            $('.log-status').addClass('wrong-entry');
                                            $('.error').fadeIn(500);
                                            setTimeout("$('.error').fadeOut(1500);", 3000);
                                        }
                                    },
                                    dataType: 'text'
                                }
                        );
                    }
                });

                //Validation à l'appui de entrer
                $("#loginFormID").keypress(function (e) {
                    var key = e.which;
                    if (key == 13)  // the enter key code
                    {
                        $('.log-btn').click();
                        return false;
                    }
                });
                //Je suis d'accord
                $('.form-control').keypress(function () {
                    $('.log-status').removeClass('wrong-entry');
                });
            })
        </script>
        <style>

            /*background: url('http://i.imgur.com/Eor57Ae.jpg') no-repeat fixed center center;
            background-color: grey;
            //vertical-align: center; margin-top: 150px;*/
            body {
                background: url('images/orders-images/background-topcosmetics.jpg') no-repeat fixed center center;
                background-size: cover;
                font-family: Montserrat;
                width: 100%;
                height: 100%;
            }

            #content {
                width: 400px;
                height: 40%;
                margin: 100px auto;
                margin-top: 25vh;
                padding: 20px 0px;
                border-radius: 5px;
                background-color: #00d7fe;
            }
            /*background: url('http://i.imgur.com/fd8Lcso.png') no-repeat;  */
            .logo {
                width: 200px;
                height: 200px;
                border-radius: 50%;
                /*position: absolute;*/
                z-index: 3;
                background-color: #fff;
                border: 2px solid #ff00b8;
                background-image:   url("images/orders-images/logo-topcosmetics.png");
                background-repeat: no-repeat;
                margin: 0px auto;
            }

            .login-block {
                width: 320px;
                padding: 20px;
                background: #fff;
                border-radius: 5px;
                border-top: 5px solid #ff00b8;
                margin: 0px auto;
            }

            .login-block h1 {
                text-align: center;
                color: #ff00b8;
                font-size: 18px;
                font-weight: bold;
                text-transform: uppercase;
                margin-top: 0;
                margin-bottom: 20px;
            }

            .login-block input {
                width: 100%;
                height: 42px;
                box-sizing: border-box;
                border-radius: 5px;
                border: 1px solid #ccc;
                margin-bottom: 20px;
                font-size: 14px;
                font-family: Montserrat;
                padding: 0 20px 0 50px;
                outline: none;
            }

            .login-block input#compte {
                background: #fff url('images/orders-images/u0XmBmv.png') 20px top no-repeat;
                background-size: 16px 80px;
            }

            .login-block input#compte:focus {
                background: #fff url('images/orders-images/u0XmBmv.png') 20px bottom no-repeat;
                background-size: 16px 80px;
            }
            
            .login-block input#email {
                background: #fff url('images/orders-images/enveloppe.png') 20px top no-repeat;
                background-size: 16px 80px;
            }

            .login-block input#email:focus {
                background: #fff url('images/orders-images/enveloppe.png') 20px bottom no-repeat;
                background-size: 16px 80px;
            }

            .login-block input#password {
                background: #fff url('images/orders-images/Qf83FTt.png') 20px top no-repeat;
                background-size: 16px 80px;
            }

            .login-block input#password:focus {
                background: #fff url('images/orders-images/Qf83FTt.png') 20px bottom no-repeat;
                background-size: 16px 80px;
            }

            .login-block input:active, .login-block input:focus {
                border: 1px solid #ff656c;
            }

            #content .alert{
                display:none;
                font-size:12px;
                color:#f00;
                float:left;
            }
            #content .error{
                display:none;
                font-size:12px;
                color:#f00;
                float:left;
                margin:10px;
            }
            #content .success {
                display:none;
                font-size:12px;
                color:green;
                float:left;  
            }
            .login-block button {
                width: 100%;
                height: 40px;
                background: #ff00b8;
                box-sizing: border-box;
                border-radius: 5px;
                border: 1px solid #e15960;
                color: #fff;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 14px;
                font-family: Montserrat;
                outline: none;
                cursor: pointer;
            }

            .login-block button:hover {
                background: #ff7b81;
            }

        </style>
    </body>
</html>