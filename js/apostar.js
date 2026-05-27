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

        }else{

            alert(data.message);

        }

    });

});