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