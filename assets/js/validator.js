class Validator {
    constructor(config) {

        this.elementsConfig = config;
        this.errors = {};
        this.generateErrorsObject();
        this.inputListener();
    }
    generateErrorsObject() {
        for (let element in this.elementsConfig) {
            this.errors[element] = [];
        }
    }
    inputListener() {
        let inputSelector = this.elementsConfig;
        for (let element in inputSelector) {
           let selector = `input[name=${element}]`;
           let el = document.querySelector(selector);
           el.addEventListener('input',this.validate.bind(this));
        }
    }
    validate(e) {
        let Elements = this.elementsConfig;
        let el = e.target;
        let elName = el.getAttribute('name');
        let elValue = el.value;
        this.errors[elName] = [];
        if(Elements[elName].required) {
            if(elValue === '') {
                this.errors[elName].push('This field is required');
            }
        }
        if(Elements[elName].email) {
            if(!this.validateEmail(elValue)) {
                this.errors[elName].push('Invalid email format. Example: jhondoe@gmail.com');
            }
        }
        if(!Elements[elName].regex.test(elValue)) {
            this.errors[elName].push(Elements[elName].error_message);
        }
        if(Elements[elName].matching) {
            let matchingElement = document.querySelector(`input[name="${Elements[elName].matching}"]`);
            if(elValue !== matchingElement.value) {
                this.errors[elName].push('Passwords do not match');
            }
        }
        if(this.errors[elName].length === 0) {
           this.errors[elName] = [];
           this.errors[Elements[elName].matching] = [];
        }
        this.populateErrors(this.errors);

    }
    populateErrors(errors) {
       for(const elem of document.querySelectorAll('ul')) {
           elem.remove();
       }
       for(let key of Object.keys(errors)) {
        let parentElement = document.querySelector(`input[name="${key}"]`).parentElement;
        let errorsElements = document.createElement('ul');
        parentElement.appendChild(errorsElements);

        errors[key].forEach(error => {
            let li = document.createElement('li');
            li.innerHTML = error;
            errorsElements.appendChild(li);
        })         
    }
    }
    validateEmail(email) {
        if(/^[a-z0-9]+[\._]?[a-z0-9]+[@]\w+[.]\w{2,3}$/.test(email)) 
        {
            return true;
        }
        return false;
    }
}