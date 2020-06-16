var editApp = {
    inputImage: document.querySelector('#user_edit_image'),
    inputFirstname: document.querySelector('#user_edit_name'),
    profilePicture: document.querySelector('#profile_picture'),
    profileName: document.querySelector('#profile_name'),
    resized: false,
    init: () => {
        // image
        editApp.inputImage.addEventListener('onchange', editApp.loadFile)
        window.addEventListener('resize', editApp.resizeWindow)
        
        // input name
        editApp.profileName.textContent = editApp.inputFirstname.value
        editApp.inputFirstname.addEventListener('input', editApp.replaceName)
    },
    loadFile: (e) => {
        console.log('change')

        let is = event.target.files[0].name.match(/(\.|jpg|png|svg|jpeg)$/) // file extensions

        if (is === null)
            return
        
        editApp.profilePicture.src = URL.createObjectURL(event.target.files[0])
        
        // adaptative size
        editApp.profilePicture.style.height = `${editApp.profilePicture.offsetWidth}px`
        editApp.resized = true
        console.log(event.target.files[0])
    },
    resizeWindow: (e) => {
        // image upload ? resize : nothing
        if (editApp.resized) {
            console.log('resized')
            editApp.profilePicture.style.height = `${editApp.profilePicture.offsetWidth}px`
        }
    },
    replaceName: () => {
        editApp.profileName.textContent = editApp.inputFirstname.value
    }
}

document.addEventListener('DOMContentLoaded', editApp.init)