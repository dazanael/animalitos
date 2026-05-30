const btn_text_registrarse =
    document.getElementById("btn_text");

const registro_contenedor =
    document.getElementById("sign_up_box");

const login_contenedor =
    document.getElementById("login_box");

const backToLogin =
    document.getElementById("back_to_login");

btn_text_registrarse.addEventListener(
    "click",
    () => {

        registro_contenedor.classList.remove(
            "invisible"
        );

        login_contenedor.classList.add(
            "invisible"
        );

    }
);


backToLogin.addEventListener(
    "click",
    () => {

        registro_contenedor.classList.add(
            "invisible"
        );

        login_contenedor.classList.remove(
            "invisible"
        );

    }
);
