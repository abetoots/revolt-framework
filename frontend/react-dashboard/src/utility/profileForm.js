// *DO NOT DELETE, used by profile reducer and profile container
import cloneDeep from 'lodash.clonedeep';
import { prefix } from './utility';

const form = {
    basicInfo: {
        heading: 'Basic Information',
        info: {
            company_name: {
                elementType: 'input',
                label: 'Employer / Company Name',
                elementConfig: {
                    type: 'text',
                    placeholder: 'Employer / Company Name',
                    required: true
                },
                value: '',
                validation: {
                    required: true
                },
                valid: false,
                touched: false
            },
            headline: {
                elementType: 'input',
                label: 'Headline',
                elementConfig: {
                    type: 'text',
                    placeholder: 'A headline that appears with your name',
                    required: true
                },
                value: '',
                validation: {
                    required: true
                },
                valid: false,
                touched: false
            },
            established: {
                elementType: 'input',
                label: 'Established Since',
                elementConfig: {
                    type: 'number',
                    placeholder: 'Zip Code',
                    required: true
                },
                value: '',
                validation: {
                    required: true,
                    minLength: 4,
                    maxLength: 6
                },
                valid: false,
                touched: false
            },
            teamSize: {
                elementType: 'input',
                label: 'No. of Employees',
                elementConfig: {
                    type: 'text',
                    placeholder: 'Size of your company/team',
                    required: true
                },
                value: '',
                validation: {
                    required: true
                },
                valid: false,
                touched: false
            },
            website: {
                elementType: 'input',
                label: 'Website',
                elementConfig: {
                    type: 'url',
                    placeholder: 'domain.com',
                    required: true
                },
                value: '',
                validation: {
                    required: true,
                    isUrl: true
                },
                valid: false,
                touched: false
            },
            description: {
                elementType: 'textarea',
                label: 'About/Description',
                elementConfig: {
                    maxLength: 500
                },
                value: '',
                validation: {},
                valid: true
            }
        }
    }, // end basicinfo
    socialInfo: {
        heading: 'Edit Social Profiles (Optional)',
        info: {
            facebook: {
                elementType: 'input',
                label: 'Facebook',
                elementConfig: {
                    type: 'url',
                },
                value: '',
                validation: {
                    isUrl: true
                },
                valid: true,
                touched: false
            },
            linkedin: {
                elementType: 'input',
                label: 'LinkedIn',
                elementConfig: {
                    type: 'url',
                },
                value: '',
                validation: {
                    isUrl: true
                },
                valid: true,
                touched: false
            },
            twitter: {
                elementType: 'input',
                label: 'Twitter',
                elementConfig: {
                    type: 'text',
                    placeholder: 'Twitter Handler: @myTwitterHandler'
                },
                value: '',
                validation: {
                    isTwitter: true
                },
                valid: true,
                touched: false
            }
        }
    }, //end social info
    contactInfo: {
        heading: 'Edit Contact Information',
        info: {
            phoneNum: {
                elementType: 'input',
                label: 'Phone Number',
                elementConfig: {
                    type: 'text',
                    required: true
                },
                value: '',
                validation: {
                    required: true,
                    isPhoneNum: true,
                    minLength: 4,
                    maxLength: 16
                },
                valid: false,
                touched: false
            },
            companyCat: {
                elementType: 'select',
                label: 'Company Industry/Category',
                elementConfig: {
                    options: [
                        { value: '', displayValue: '' },
                    ]
                },
                value: '',
                validation: {},
                valid: true,
            },
            contactEmail: {
                elementType: 'input',
                label: 'Email',
                elementConfig: {
                    type: 'email',
                    required: true
                },
                value: '',
                validation: {
                    required: true,
                    isEmail: true
                },
                valid: false,
                touched: false
            },
        }

    }, //end contact info
}

export const profileForm = cloneDeep(form);
/**
 * Renames all our field name keys e.g. "company_name" to PREFIX + "company_name"
 * @returns String example: "revolt_company_name"
 */
for (let groupKey in profileForm) {
    for (let infoKey in profileForm[groupKey].info) {
        profileForm[groupKey].info[prefix + infoKey] = profileForm[groupKey].info[infoKey];
        delete profileForm[groupKey].info[infoKey];
    }
}

// Used by profile reducer
//! Doesnt need to rename keys again, since we reference profileForm which has already been renamed
export const employerInfo = {};
for (let groupKey in profileForm) {
    for (let infoKey in profileForm[groupKey].info) {
        employerInfo[infoKey] = "";
    }
}
