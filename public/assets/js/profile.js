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
        profileApp.fetchFormData(input.value, form.id)
    },
    displayError: (message, form) => {

        const divError = form.querySelector('#error')
        divError.innerHTML = `
        <div class="bg-red-700 text-white rounded-md font-bold p-3 mr-2">
            ${message}
        </div>`
    },
    fetchFormData: (data, path) => {
        
    }
}

document.addEventListener('DOMContentLoaded', profileApp.init)