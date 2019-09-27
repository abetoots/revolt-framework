import React, { Component } from 'react';
import './PostJob.scss';
//Redux
import { connect } from 'react-redux';
import * as actions from '../../store/actions/index';

//UI
import FroalaEditor from 'react-froala-wysiwyg';
import ACFInput from '../../components/UI/ACFInput/ACFInput';
import Spinner from '../../components/UI/Spinner/Spinner';

//Froala Editor
// Require Editor JS files.
import 'froala-editor/js/froala_editor.pkgd.min.js';

// Require Editor CSS files.
import 'froala-editor/css/froala_style.min.css';
import 'froala-editor/css/froala_editor.pkgd.min.css';

//Utility
import Aux from '../../hoc/Auxiliary';
import { checkFieldValidity } from '../../utility/utility';

//Forms
import { jobPostFormBasic, jobPostFormTaxonomies, buildFormData } from '../../acf-json/forms';

import getNested from 'lodash.get';
import setNested from 'lodash.set';
import { isArray } from 'util';

class PostJob extends Component {
    state = {
        jobPostBasicInfoForm: jobPostFormBasic,
        jobPostTaxonomiesForm: jobPostFormTaxonomies,
        formIsValid: false,
        model: ''
    }

    componentDidMount() {
        if (!this.props.fetchedTaxonomies) {
            this.props.fetchTaxonomiesOnMount();
        }
    }

    handleModelChange = (model) => {
        console.log(model)
        this.setState({ model: model });
    }

    submitHandler(event) {
        event.preventDefault();
        const basicInfoData = buildFormData(this.state.jobPostBasicInfoForm.fields);
        const taxonomiesData = buildFormData(this.state.jobPostTaxonomiesForm.fields);
        const formData = {
            content: this.state.model,
            //set job_acf_fields as combined basicinfo and taxonomies obj
            job_acf_fields: Object.assign(basicInfoData, taxonomiesData)
        };

        console.log(formData)
        // this.props.onPostJob(formData, this.props.job.id, this.props.token, this.props.match.params.jobIndex);
    }

    /**
     * @param {object} event
     * @param {string} path Used to find where the input target is located in the nested form object
     * @param {string} parentForm determines which form to clone
     * @param {string} fieldType used in switch case to handle updating of different field types
     */
    inputChangedHandler = (event, path, parentForm, fieldType) => {
        /**
         * Clone the form and the specific field to update
         */
        const clonedForm = {
            ...this.state[parentForm]
        };
        const clonedField = getNested(clonedForm.fields, path);
        /**
         * Set the value, set touched to true, and check validity
         */
        switch (fieldType) {
            case ('true_false'):
                clonedField.value = event.target.checked;
                break;

            case ('taxonomy'):
                switch (event.target.name) {
                    // ! event target value should be converted back to number, hence the "+"
                    case ('job_categories'):
                        if (event.target.checked) {
                            if (!Array.isArray(clonedField.value)) {
                                clonedField.value = [];
                            }
                            clonedField.value.push(+event.target.value);
                        } else {
                            clonedField.value = clonedField.value.filter(val => val !== +event.target.value);
                        }
                        break;
                    case ('employment_types'):
                        clonedField.value = +event.target.value;
                        break;
                    default:
                        clonedField.value = +event.target.value;
                }
                break;
            default:
                clonedField.value = event.target.value;
        }
        //let our state know if the input has been touched
        clonedField.touched = true;
        //checkFieldValidity returns true/false
        clonedField.valid = checkFieldValidity(event.target.value, clonedField);
        // replace clonedform's appropriate field with the cloned field
        setNested(clonedForm.fields, path, clonedField);
        this.setState({ [parentForm]: clonedForm });
    }

    /**
     * A recursive function made for mapping the ACF json structure to <ACFInput/> Components
     * We pass in our fields array, and if it finds sub_fields, will call itself 
     * @param {Array} fields 
     * @param {String} path
     * @param {String} parentForm
     */
    mapInputs(fields, path = '', parentForm, excludedFieldName = '') {
        const returnThis = fields.map((field, index) => {
            if (field.name === excludedFieldName) {
                return '';
            }
            let inputField =
                //ACF input
                <ACFInput
                    key={field.key}
                    label={field.label}
                    name={field.name}
                    inputType={field.type}
                    instructions={field.instructions}
                    fieldType={field.field_type ? field.field_type : ''}
                    taxonomyOptions={this.props.taxonomies[field.taxonomy] ? this.props.taxonomies[field.taxonomy] : []}
                    defaultChoices={field.choices ? Object.entries(field.choices) : ''}
                    value={field.value}
                    valid={field.valid}
                    shouldValidate={field.required === 1 ? true : false}
                    touched={field.touched}
                    changed={(event) => {
                        //if path exists, append .sub_fields[index] to path
                        let pathName = '';
                        path ? pathName = `${path}.sub_fields[${index}]` : pathName = `[${index}]`;
                        return this.inputChangedHandler(event, pathName, parentForm, field.type);
                    }}
                />;

            if (field.sub_fields) {
                //CURRENT FIELD HAS SUB FIELDS
                //if path exists, append .sub_fields[index] to path
                let pathName = '';
                path ? pathName = `${path}.sub_fields[${index}]` : pathName = `[${index}]`;
                return (
                    <div key={field.key} className="PostJob__nestedField">
                        {path ? <h4 className="PostJob__subLabel">{field.label}</h4> : <h3 className="PostJob__parentLabel">{field.label}</h3>}
                        {this.mapInputs(field.sub_fields, pathName, parentForm, field.type)}
                    </div>
                );
            }
            return inputField;
        }) //end map

        return returnThis;
    }

    render() {
        let rendered = this.props.errorTax ? <p>Job Form could not be loaded</p> : <Spinner />;
        let updateStatus = '';
        let editJobStatusClasses = ['PostJob__status'];
        if (this.props.editSuccess) {
            editJobStatusClasses.push('-success');
            updateStatus =
                <div>Updated! <span role="img" aria-label="update-success">✅</span></div>
        } else if (this.props.editError) {
            editJobStatusClasses.push('-failed');
            updateStatus =
                <div>Something went wrong! <span role="img" aria-label="update-failed">❌</span></div>
        }
        if (!this.props.loading) {
            //! jsx should be in array
            const formBasicInfo = [this.state.jobPostBasicInfoForm];
            const formTaxonomies = [this.state.jobPostTaxonomiesForm];
            rendered =
                <form onSubmit={(event) => this.submitHandler(event)} className="PostJob__form">
                    <div className="PostJob__jobContent">
                        <h2 className="PostJob__heading">Content</h2>
                        <FroalaEditor
                            model={this.state.model}
                            onModelChange={this.handleModelChange}
                            config={{
                                imageUpload: false
                            }}
                        />
                    </div>
                    <div className="PostJob__fields">
                        {
                            formBasicInfo.map(formEl => {
                                return (
                                    <Aux key={formEl.key}>
                                        <h2 className="PostJob__heading">{formEl.title}</h2>
                                        {this.mapInputs(formEl.fields, '', 'jobPostBasicInfoForm')}
                                    </Aux>
                                );
                            })
                        }
                    </div>
                    <div className="PostJob__fields">
                        {
                            formTaxonomies.map(form2El => {
                                return (
                                    <Aux key={form2El.key}>
                                        <h2 className="PostJob__heading">{form2El.title}</h2>
                                        {this.mapInputs(form2El.fields, '', 'jobPostTaxonomiesForm', 'the_premium_package')}
                                    </Aux>
                                );
                            })
                        }
                    </div>
                    <div className="PostJob__btnContainer">
                        {/* //TODO thispropsediting */}
                        <button className="PostJob__submitBtn" style={{ cursor: this.props.editing ? 'wait' : '' }} type="submit">Post Your Job</button>
                    </div>
                </form >;
        };

        return (
            <div className="PostJob" >
                <div className={editJobStatusClasses.join(' ')}>
                    {updateStatus}
                </div>
                {rendered}
            </div>
        );
    }
};

const mapStateToProps = (state, ownProps) => {
    return {
        //fetching and rendering
        taxonomies: state.taxonomies.taxonomies,
        loading: state.taxonomies.loading,
        errorTax: state.taxonomies.error,
        fetchedTaxonomies: state.taxonomies.fetched,
        //submit
        token: state.auth.token,
    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchTaxonomiesOnMount: () => dispatch(actions.fetchTaxonomies()),
        // onPostJob: (formData, jobId, token, jobIndex) => dispatch(actions.postJob(formData, jobId, token, jobIndex))
    }
}


export default connect(mapStateToProps, mapDispatchToProps)(PostJob);