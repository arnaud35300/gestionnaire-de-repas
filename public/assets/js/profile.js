var profileApp = {
    init: () => {
        const forms = document.querySelectorAll('.ajax')

        for (i = 0; i < forms.length; i++)
            forms[i].addEventListener('submit', profileApp.handleSubmitForm)
    },
    handleSubmitForm: (e) => {
        e.preventDefault()

        const form = e.currentTarget
        const input = form.querySelector('textarea')

        if (input.value.trim() === '' || input.value.length > 200) {
            profileApp.displayError('Your message must be between 5 and 200 characters', form)
            return
        }
        profileApp.fetchFormData(input.value, form)
    },
    displayError: (message, form) => {

        const divError = form.querySelector('#error')
        divError.innerHTML = `
        <div class="bg-red-700 text-white rounded-md font-bold p-3 mr-2">
            ${message}
        </div>`
    },
    displaySuccess: async (message, form) => {
        // delete all messages with class=flash
        const output = form.querySelector('#output')
        const textarea = form.querySelector('textarea')

        textarea.value = ''

        // form.removeChild(output)

        document.querySelector('#flash-container').innerHTML = `
        <div style="z-index: -1;" class="relative flash alert alert-success mx-3 animate__animated animate__backOutUp animate__delay-5s">
			${message}
		</div>
        `
        console.log('created')

        /* TODO 
            Cree une promise afin de stopper le script le temps du settimeout dans flash.deleteMessages
            Le probleme est que si on appelle un form en dessous de ce settimeout, ca relance l'action
            hors, si l'action est relance, elle fait sa liste de variable flash message mais elles sont supprimees entre temps
            par la premiere action
            Il faut donc attendre 5 sec entre deux requetes
        */ 
        const result = flash.deleteMessages()

        console.log('end')
    },
    fetchFormData: async (data, form) => {
        try {
            const res = await fetch(`../api/${form.id}`, {
                method: 'POST',
                body: JSON.stringify({
                    content: data
                }),
                headers: {
                    'Content-type': 'application/json'
                }
            })
            if (res.ok) {
                const jsonData = await res.json
                const display = await profileApp.displaySuccess('Your message has been send, you will receive a response soon!', form)
            } else {
                console.error(`server response : ${res.status}`)
            }
        } catch(error) {
            console.error(error)
        }
    }
}

document.addEventListener('DOMContentLoaded', profileApp.init)