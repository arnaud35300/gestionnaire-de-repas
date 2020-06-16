var profileApp = {
    helpForm: document.querySelector('#message-form'),
    contactForm: document.querySelector('#contact-form'),
    init: () => {

        profileApp.helpForm.addEventListener('submit', profileApp.handleSubmitForm())
        profileApp.contactForm.addEventListener('submit', profileApp.handleSubmitForm())
    },
    handleSubmitForm: () => function() {

    }
}

document.addEventListener('DOMContentLoaded', profileApp.init)