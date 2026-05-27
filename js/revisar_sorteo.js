fetch("../php/crear_sorteo.php")
    .then(res => res.text())
    .then(data => {
        console.log(data);
    });
setInterval(() => {
    fetch("../php/crear_sorteo.php")
        .then(res => res.text())
        .then(data => {
            console.log(data);
        });

}, 60000);