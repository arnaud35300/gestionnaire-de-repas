const app = {
    menu: document.querySelector("#menu"),
    init: () => {
        let button = document.querySelector('#button')
        var x = true

        if (window.innerWidth < 1024)
            menu.style.display = 'none'

        button.addEventListener('click', () => {
            let display = menu.style.display === "none" ? menu.style.display = "block" : menu.style.display = "none"
        })

        window.addEventListener('resize', () => {
            if (window.innerWidth < 1024 && x) {
                menu.style.display = 'none'
                x = false
            }
            if (window.innerWidth >= 1024) {
                menu.style.display = 'flex'
                x = true
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', app.init)


