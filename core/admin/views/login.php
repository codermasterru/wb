<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
    <style>

        html, body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: stretch;
            background-color: #ffffff;
            padding: 15px;
            gap: 5px;
        }

        div {
            flex-basis: 500px;
            padding: 15px;
            color: #d06524;
            font-size: 16px;
        }

        form {
            display: block;
        }

        label, input {
            display: block;
            margin: auto;
        }

        label, h1 {

            text-align: center;
            padding-bottom: 7px;
        }

        input {
            margin-bottom: 20px;
            padding: 3px 5px;
        }


        input[type=submit] {
            background: #d06524;
            padding: 15px 60px;
            color: white;
            border: none;
            border-radius: 5px;
        }


    </style>
</head>
<body>

<div>
    <h1>Авторизация</h1>
    <form action="<?= PATH . $adminPath ?>/login" method="post">

        <label for="login">Логин</label>
        <input type="text" name="login" id="login">
        <label for="password">Пароль</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Войти">

    </form>
</div>


<script src="<?= PATH . ADMIN_TEMPLATE ?>js/frameworkfunctions.js"></script>

<script>

    let form = document.querySelector('form');

    if (form) {

        form.addEventListener('submit', e => {

            if (e.isTrusted) {

                e.preventDefault();

                Ajax({data: {ajax: 'token'}}).then(res => {

                    if (res) {

                        form.insertAdjacentHTML('beforeend',`<input type="hidden" name="token" value="${res}">`);

                    }

                    form.submit()

                });

            }

        })

    }

</script>
</body>
</html>
