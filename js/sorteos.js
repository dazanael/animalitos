let animal_seleccionado = null
const animales = document.querySelectorAll(".animal")


animales.forEach(animal =>{
    animal.addEventListener("click", ()=>{
        if(animal_seleccionado!=null){
            animal_seleccionado.classList.remove("selected")
        }
        animal_seleccionado = animal
        animal_seleccionado.classList.add("selected")
    })
})

const timeBox = document.getElementById("time_box");

function actualizarContador() {

    const fechaObjetivo = new Date(
        `${fechaSorteo}T${horaSorteo}`
    );

    const ahora = new Date();

    const diferencia = fechaObjetivo - ahora;

    if(diferencia <= 0){

        timeBox.innerHTML = `
            <p>Min:</p>
            <p>00</p>
            <p>Seg:</p>
            <p>00</p>
        `;

        return;
    }

    const minutos = Math.floor(diferencia / 1000 / 60);
    const segundos = Math.floor((diferencia / 1000) % 60);

    timeBox.innerHTML = `
        <p>Min:</p>
        <p>${String(minutos).padStart(2, '0')}</p>
        <p>Seg:</p>
        <p>${String(segundos).padStart(2, '0')}</p>
    `;

}

actualizarContador();

setInterval(actualizarContador, 1000);

const lastWinnerImg = document.getElementById("last_winner_img");
const lastWinnerName = document.getElementById("last_winner_name");

function cargarUltimoGanador() {

    fetch("../php/obtener_ultimo_ganador.php")
        .then(res => res.json())
        .then(data => {

            if(!data){
                return;
            }

            lastWinnerImg.src = "../" + data.url_imagen;

            lastWinnerName.textContent =
                data.numero + " " + data.nombre;

        });

}

cargarUltimoGanador();

setInterval(cargarUltimoGanador, 5000);

const animals = document.querySelectorAll(".animal");

const moneyInput = document.getElementById("money_input");

let animalSeleccionado = null;

animals.forEach(animal => {

    animal.addEventListener("click", () => {

        animals.forEach(a => {
            a.classList.remove("selected");
        });

        animal.classList.add("selected");

        animalSeleccionado = animal.dataset.id;

    });

});

function cargarTotales() {

    fetch("../php/obtener_totales.php")
        .then(res => res.json())
        .then(data => {

            data.forEach(item => {

                const total = document.querySelector(
                    `.animal_total[data-id="${item.id}"]`
                );

                if(total){

                    total.textContent = item.total + "$";

                }

            });

        });

}

cargarTotales();

setInterval(cargarTotales, 5000);