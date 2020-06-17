const flash = {
    init: () => {
        flash.deleteMessages()
    },
    deleteMessages: () => {
        let messages = document.querySelectorAll('.flash')

        messages.forEach(message => {
            const container = document.querySelector('#flash-container')
            console.log(container)
            setTimeout(() => {
                container.removeChild(message)
            }, 5500)
        });
    }
}

document.addEventListener('DOMContentLoaded', flash.init)