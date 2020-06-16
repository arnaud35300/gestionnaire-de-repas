const flash = {
    messages: document.querySelectorAll('.flash'),
    init: () => {
        setTimeout(() => {
            flash.deleteMessages()
        }, 5000)
    },
    deleteMessages: () => {
        flash.messages.forEach(message => {
            const body = document.querySelector('body')
            body.removeChild(message)
        });
    }
}

document.addEventListener('DOMContentLoaded', flash.init)