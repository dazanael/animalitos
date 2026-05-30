const botones = document.querySelectorAll(".tab_button");

const paneles = document.querySelectorAll(".history_panel");

botones.forEach(boton => {

    boton.addEventListener("click", () => {

        botones.forEach(b => {
            b.classList.remove("active");
        });

        paneles.forEach(panel => {
            panel.classList.remove("active");
        });

        boton.classList.add("active");

        const objetivo = boton.dataset.tab;

        document
            .getElementById(objetivo)
            .classList
            .add("active");

    });

});