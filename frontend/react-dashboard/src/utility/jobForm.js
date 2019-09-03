import cloneDeep from 'lodash.clonedeep';

const form = {
    basicInfo: {
        heading: 'Basic Information',
        info: {
            title: {
                elementType: 'input',
                label: 'Job Title',
                elementConfig: {
                    type: 'text',
                    placeholder: 'Example: \'React Developer\' '
                },
                value: '',
                validation: {
                    required: true
                },
                valid: false,
                touched: false
            },
            category: {
                elementType: 'select',
                label: 'Job Category',
                elementConfig: {
                    options: [
                        { value: 'marketing', displayValue: 'Marketing' },
                        { value: 'webdesign', displayValue: 'UI/UX Web Design' },
                        { value: 'graphicdesign', displayValue: 'Graphic Designer' },
                    ]
                },
                value: '',
                validation: {},
                valid: true
            },
            description: {
                elementType: 'editor',
                label: 'Job Description',
                elementConfig: {
                    //refer to Froala doc Options
                    charCounterCount: false
                },
                value: '',
                validation: {
                    required: true
                },
                valid: false,
                touched: false
            }
        }
    }, //end basic info
    jobDetails: {
        heading: 'Job Details',
        info: {
            deadline: {
                elementType: 'input',
                label: 'Application Deadline',
                elementConfig: {
                    type: 'text',
                    placeholder: 'Dummy Date',
                    disabled: true
                },
                value: '',
                validation: {
                    required: true
                },
                valid: true,
            },
            qualification: {
                elementType: 'select',
                label: 'Job Qualifications (Optional)',
                elementConfig: {
                    options: [
                        { value: 'marketing', displayValue: 'Marketing' },
                        { value: 'webdesign', displayValue: 'UI/UX Web Design' },
                        { value: 'graphicdesign', displayValue: 'Graphic Designer' },
                    ]
                },
                value: '',
                validation: {},
                valid: true
            },
            jobType: {
                elementType: 'select',
                label: 'Job Type',
                elementConfig: {
                    options: [
                        { value: 'marketing', displayValue: 'Marketing' },
                        { value: 'webdesign', displayValue: 'UI/UX Web Design' },
                        { value: 'graphicdesign', displayValue: 'Graphic Designer' },
                    ]
                },
                value: '',
                validation: {},
                valid: true,
            }

        }
    }, //end job details
}

export const jobForm = cloneDeep(form);

export const manageJobForm = cloneDeep(form);