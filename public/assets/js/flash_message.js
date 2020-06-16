const flash = {
    messages: document.querySelectorAll('.flash'),
    init: () => {
            flash.deleteMessages()
    },
    deleteMessages: () => {
        flash.messages.forEach(message => {
            const body = document.querySelector('body')
            setTimeout(() => {
                body.removeChild(message)
            }, 5500)
        });
    }
}

document.addEventListener('DOMContentLoaded', flash.init)