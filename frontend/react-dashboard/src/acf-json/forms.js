import JobPostSettings from './group_5d4691841b74f';
import JobPostTaxonomies from './group_5d48efa3079cc';

import get from 'lodash.get';
import set from 'lodash.set';

/**
 * Updates ALL of an ACF field group's fields
 * If the parent field has sub fields, the function will call itself, so it only updates the sub_fields
 * 
 * @param {Array} fields The array of fields from ACF's json data structure
 * @param {Array} fieldToUpdate Sample: [['value', ''], ['valid', false]] adds/updates {value: '', valid: false}
 */
const recursivelyUpdateFields = (fields, fieldToUpdate) => {

    fields.forEach(field => {

        //if the field has sub_fields , call itself recursively
        if (field.sub_fields) {
            recursivelyUpdateFields(field.sub_fields, fieldToUpdate);
        } else {
            //update fields according to fieldToUpdate array
            fieldToUpdate.forEach(object => {
                field[object[0]] = object[1]
            })
        }
    });
    return fields;
}

/**
 * Takes a field group updates it's fields
 * @param {Object} setting An ACF JSON field group
 * @param {Array} fieldsToUpdate
 * @uses recursivelyUpdateFields()
 * @returns The field group with updated fields
 */
const updateSettingFields = (setting, fieldsToUpdate) => {
    setting.fields = recursivelyUpdateFields(setting.fields, fieldsToUpdate);
    return setting;
}

export const jobPostFormBasic = updateSettingFields(JobPostSettings, [['value', ''], ['valid', false], ['touched', false]]);
export const jobPostFormTaxonomies = updateSettingFields(JobPostTaxonomies, [['value', ''], ['valid', false], ['touched', false]]);


/**
 * TODO Refactor to enable user to choose which key to update 
 * Update's all of our ACF.fields 'value' key. Works like our function above with additional steps,
 * If it finds a sub_field key, call itself AND push the the current field.name to the 3rd param 'path'
 * 
 * Example use case: 
 * We call the function, pass in our fields(ACF JSON field group fields array)
 * The forEach loop runs, if it finds sub_fields at the 'job_salary' field, call itself and pass the 'job_salary' in the 3rd param
 * Now on the sub_fields forEach loop, we can extract our data from the 2nd param like so:
 * get(jobPostData.jobFields, ['job_salary']);
 * 
 * If we find another nested sub_fields at 'range', on the forEach loop, it becomes like so:
 * get(jobPostData.jobFields, [''job_salary', 'range'])
 * 
 * @param {Array} fields 
 * @param {Array} jobPostData 
 * @param {Array} path 
 * @uses lodash.get https://lodash.com/docs/4.17.15#get
 */
export const recursivelyUpdateFieldValues = (fields, jobPostData, path = '') => {
    fields.forEach(field => {
        if (field.sub_fields) {
            let pathName = `${path}[${field.name}]`;
            return recursivelyUpdateFieldValues(field.sub_fields, jobPostData, pathName)
        } else {
            let pathName = `${path}[${field.name}]`;
            field.value = get(jobPostData.jobFields, pathName);
        }
    });
    return fields;
}

export const updateTaxonomyValues = (fields, jobPostData) => {
    fields.forEach(field => {
        field.value = jobPostData.jobFields[field.name]
    })
    return fields;
}

export const buildFormData = (fields, path = '', formData = {}) => {
    fields.forEach(field => {
        if (field.sub_fields) {
            let pathName = `${path}[${field.name}]`;
            buildFormData(field.sub_fields, pathName, formData);
        } else {
            let pathName = `${path}[${field.name}]`;
            set(formData, pathName, field.value);
        }
    })
    return formData;
}