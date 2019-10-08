export const updateObject = (oldState, newProperties) => {
    return {
        ...oldState,
        ...newProperties
    }
}

export const checkFieldValidity = (value, field) => {
    let isValid = true;
    if (field.required === 1) {
        isValid = value !== '' && isValid;
    }
    let regex = '';
    switch (field.type) {

        case ('email'):
            regex = new RegExp('^(([^<>()\\[\\]\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
            isValid = regex.test(value) === true && isValid;
            break;

        case ('number'):
            field.label === 'Phone Number' ?
                regex = new RegExp('^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$', 'g') :
                regex = new RegExp('^[0-9]*$', 'gm');
            isValid = regex.test(value) === true && isValid;
            break;

        case ('url'):
            regex = new RegExp('((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9\\.\\-]+|(?:www\\.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9\\.\\-]+)((?:\\/[\\+~%\\/\\.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@\\.\\w_]*)#?(?:[\\.\\!\\/\\\\w]*))?)');
            isValid = regex.test(value) === true && isValid;
            break;

        case ('taxonomy'):
            if (field.field_type === 'checkbox') {
                isValid = Array.isArray(value) && value.length !== 0 && isValid;
            } else {
                isValid = value !== '' && isValid;
            }
            break;

        default:
            return isValid;

    }

    if (field.min) {
        isValid = value >= field.min && isValid;
    }

    if (field.max) {
        isValid = value <= field.max && isValid;
    }

    if (field.twitterValidate) {
        let regex = new RegExp('^@?(\\w){1,15}$');
        isValid = regex.test(value) === true && isValid;
    }

    return isValid;
}

export const checkInputValidity = (value, rules) => {
    let isValid = true;

    if (rules.required) {
        isValid = value.trim() !== '' && isValid;
    }

    if (rules.isEmail) {
        let regex = new RegExp('^(([^<>()\\[\\]\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$');
        isValid = regex.test(value) === true && isValid;
    }

    if (rules.isPhoneNum) {
        let regex = new RegExp('^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\\s\\./0-9]*$', 'g');
        isValid = regex.test(value) === true && isValid;
    }

    if (rules.isUrl) {
        let regex = new RegExp('((([A-Za-z]{3,9}:(?:\\/\\/)?)(?:[\\-;:&=\\+\\$,\\w]+@)?[A-Za-z0-9\\.\\-]+|(?:www\\.|[\\-;:&=\\+\\$,\\w]+@)[A-Za-z0-9\\.\\-]+)((?:\\/[\\+~%\\/\\.\\w\\-_]*)?\\??(?:[\\-\\+=&;%@\\.\\w_]*)#?(?:[\\.\\!\\/\\\\w]*))?)');
        isValid = regex.test(value) === true && isValid;
    }

    if (rules.minLength) {
        isValid = value.length >= rules.minLength && isValid;
    }

    if (rules.maxLength) {
        isValid = value.length <= rules.maxLength && isValid;
    }

    if (rules.isTwitter) {
        let regex = new RegExp('^@?(\\w){1,15}$');
        isValid = regex.test(value) === true && isValid;
    }

    return isValid;
}