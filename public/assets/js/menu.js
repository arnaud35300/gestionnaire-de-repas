
document.addEventListener('DOMContentLoaded', () => {
    let button = document.querySelector('#button')
    button.addEventListener('click', () => {
        let menu = document.querySelector("#menu")
        let display = menu.style.display === "none" ? menu.style.display = "block" : menu.style.display = "none"
    })
    window.addEventListener('resize', () => {
        let menu = document.querySelector("#menu")
        if (window.innerWidth >= 1024)
            menu.style.display = 'flex'
    });
})