const animales = document.querySelectorAll(".animal");

const moneyInput = document.getElementById("money_input");

const timeBox = document.getElementById("time_box");

const lastWinnerImg = document.getElementById("last_winner_img");

const lastWinnerName = document.getElementById("last_winner_name");

let animalSeleccionado = null;
let recargando = false;

const balanceButton = document.getElementById("balance_button");

/*
|--------------------------------------------------------------------------
| Seleccionar animal
|--------------------------------------------------------------------------
*/

animales.forEach(animal => {

    animal.addEventListener("click", () => {

        animales.forEach(a => {
            a.classList.remove("selected");
        });

        animal.classList.add("selected");

        animalSeleccionado = animal.dataset.id;

    });

});

/*
|--------------------------------------------------------------------------
| Contador
|--------------------------------------------------------------------------
*/

function actualizarContador() {

    const ahora = new Date();
    const diferencia = timestampObjetivo - ahora.getTime();

    let minutos = Math.max(0, Math.floor(diferencia / 1000 / 60));
    let segundos = Math.max(0, Math.floor((diferencia / 1000) % 60));

    timeBox.innerHTML = `
        <p>Min:</p>
        <p>${String(minutos).padStart(2, '0')}</p>
        <p>Seg:</p>
        <p>${String(segundos).padStart(2, '0')}</p>
    `;

    if (diferencia <= 0 && !recargando) {
        recargando = true;
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }
}

/*
|--------------------------------------------------------------------------
| Último ganador
|--------------------------------------------------------------------------
*/

function cargarUltimoGanador() {

    fetch("../php/obtener_ultimo_ganador.php")
        .then(res => res.json())
        .then(data => {

            if(!data){
                return;
            }

            lastWinnerImg.src =
                "../" + data.url_imagen;

            lastWinnerName.textContent =
                data.numero + " " + data.nombre;

        });

}

/*
|--------------------------------------------------------------------------
| Totales apuestas
|--------------------------------------------------------------------------
*/

function cargarTotales() {

    fetch("../php/obtener_totales.php")
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                const total = document.querySelector(
                    `.animal_total[data-id="${item.id}"]`
                );

                if(total){

                    total.textContent =
                        item.total + "$";

                }

            });

        });

}

/*
|--------------------------------------------------------------------------
| Inicializar inmediatamente
|--------------------------------------------------------------------------
*/

actualizarContador();

cargarUltimoGanador();

cargarTotales();

/*
|--------------------------------------------------------------------------
| Intervalos
|--------------------------------------------------------------------------
*/

setInterval(actualizarContador, 1000);

setInterval(cargarUltimoGanador, 5000);

setInterval(cargarTotales, 5000);

/* Apuestas */

const form = document.getElementById("bet_form");

form.addEventListener("submit", e => {
    e.preventDefault();

    if(!animalSeleccionado){
        alert("Selecciona un animal");
        return;
    }

    const monto = moneyInput.value;

    const formData = new FormData();

    formData.append("animal_id", animalSeleccionado);
    formData.append("monto", monto);

    fetch("../php/apostar.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if(data.success){

            cargarTotales();

            cargarSaldo();

            const animal = document.querySelector(
                `.animal[data-id="${animalSeleccionado}"]`
            );

            if(
                animal &&
                !animal.querySelector(".cancel_bet")
            ){

                const botonCancelar =
                    document.createElement("div");

                botonCancelar.className =
                    "cancel_bet";

                botonCancelar.dataset.animalId =
                    animalSeleccionado;

                botonCancelar.innerHTML = "×";

                animal.prepend(botonCancelar);

            }

        }else{

            alert(data.message);

        }

    });

});

/* obtener saldo */

function cargarSaldo() {

    fetch("../php/obtener_saldo.php")
        .then(res => res.json())
        .then(data => {

            balanceButton.textContent =
                "$" + data.saldo;

        });

}

document.addEventListener("click", e => {

    const btn = e.target.closest(".cancel_bet");

    if(!btn){
        return;
    }

    e.preventDefault();
    e.stopPropagation();

    const animalId = btn.dataset.animalId;

    if(
        !confirm(
            "¿Cancelar todas las apuestas activas de este animal?"
        )
    ){
        return;
    }

    const formData = new FormData();

    formData.append(
        "animal_id",
        animalId
    );

    fetch("../php/cancelar_apuesta.php",{
        method:"POST",
        body:formData
    })
    .then(res => res.json())
    .then(data => {

        if(data.success){

            btn.remove();

            cargarSaldo();

            cargarTotales();

            const total = document.querySelector(
                `.animal_total[data-id="${animalId}"]`
            );

            if(total){

                total.textContent = "0$";

            }

        }else{

            alert(data.message);

        }

    });


});