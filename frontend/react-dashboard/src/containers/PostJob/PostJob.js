import React, { Component } from 'react';
import './PostJob.scss';
//Redux
import { connect } from 'react-redux';
import * as actions from '../../store/actions/index';

//UI
import FroalaEditor from 'react-froala-wysiwyg';
import ACFInput from '../../components/UI/ACFInput/ACFInput';
import Input from '../../components/UI/Input/Input';
import Spinner from '../../components/UI/Spinner/Spinner';

//Paypal
import PaypalButton from '../../components/PaypalButton/PaypalButton';

//Froala Editor
// Require Editor JS files.
import 'froala-editor/js/froala_editor.pkgd.min.js';

// Require Editor CSS files.
import 'froala-editor/css/froala_style.min.css';
import 'froala-editor/css/froala_editor.pkgd.min.css';

//Utility
import Aux from '../../hoc/Auxiliary';
import { checkFieldValidity, checkInputValidity, updateObject } from '../../utility/utility';

//Forms
import { jobPostFormBasic, jobPostFormTaxonomies, buildFormData } from '../../acf-json/forms';

import getNested from 'lodash.get';
import setNested from 'lodash.set';

class PostJob extends Component {
    constructor(props) {
        super(props);
        this.paypalRef = React.createRef();
    }

    state = {
        title: {
            elementType: 'input',
            label: 'Job Title',
            elementConfig: {
                type: 'text',
                placeholder: 'Job Title',
                required: true
            },
            value: '',
            validation: {
                required: true,
                maxLength: 127
            },
            valid: false,
            touched: false
        },
        jobPostBasicInfoForm: jobPostFormBasic,
        jobPostTaxonomiesForm: jobPostFormTaxonomies,
        formIsValid: false,
        jobPrice: 99,
        paidFor: false,
        model: ''
    }

    componentDidMount() {
        if (!this.props.fetchedTaxonomies) {
            this.props.fetchTaxonomiesOnMount();
        }
    }

    //froala editor's two way binding handler
    handleModelChange = (model) => {
        console.log(model)
        this.setState({ model: model });
    }

    approvedPaymentHandler(order) {
        console.log(order);
        this.setState({ paidFor: true })
    }

    submitHandler(event) {
        event.preventDefault();
        const basicInfoData = buildFormData(this.state.jobPostBasicInfoForm.fields);
        const taxonomiesData = buildFormData(this.state.jobPostTaxonomiesForm.fields);
        const formData = {
            title: this.state.title.value,
            content: this.state.model,
            //set job_acf_fields as combined basicinfo and taxonomies obj
            job_acf_fields: Object.assign(basicInfoData, taxonomiesData),
            status: "publish"
        };

        console.log(formData);
        this.props.onPostJob(formData, this.props.token);
    }


    titleChangedHandler = (event) => {

        //clone our title obj, update properties
        const clonedTitle = updateObject(this.state.title, {
            value: event.target.value,
            valid: checkInputValidity(event.target.value, this.state.title.validation),
            touched: true
        });

        //TODO maybe loop over to check if form is valid
        // let formIsValid = true;
        // for (let inputIdentifier in updatedOrderForm) {
        //     formIsValid = updatedOrderForm[inputIdentifier].valid && formIsValid;
        // }
        this.setState({ title: clonedTitle });
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
        clonedField.valid = checkFieldValidity(clonedField.value, clonedField);
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
                    value={field.default_value && !field.touched ? field.default_value : field.value}
                    valid={field.valid}
                    required={field.required === 1 ? true : false}
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
        //Payment status that appears on top
        let paymentStatus = '';
        const paymentStatusClasses = ['PostJob__paymentStatus'];

        //Form to be rendered
        let rendered = this.props.errorTax ? <p>Job Form could not be loaded</p> : <Spinner />;
        if (!this.props.loading) {
            //! jsx should be in array
            const formBasicInfo = [this.state.jobPostBasicInfoForm];
            const formTaxonomies = [this.state.jobPostTaxonomiesForm];
            rendered =
                <form onSubmit={(event) => this.submitHandler(event)} className="PostJob__form">
                    <div className="PostJob__jobContent">
                        <h2 className="PostJob__heading">Job Position/Title</h2>
                        <Input
                            value={this.state.title.value}
                            elementType={this.state.title.elementType}
                            valid={this.state.title.valid}
                            shouldValidate={this.state.title.validation}
                            touched={this.state.title.touched}
                            elementConfig={this.state.title.elementConfig}
                            changed={(event) => this.titleChangedHandler(event)}
                        />
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
                                        {this.mapInputs(formEl.fields, '', 'jobPostBasicInfoForm', '')}
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
                                        {this.mapInputs(form2El.fields, '', 'jobPostTaxonomiesForm', '')}
                                    </Aux>
                                );
                            })
                        }
                    </div>
                    <div className="PostJob__btnContainer">
                        <button className="PostJob__submitBtn" style={{ cursor: this.props.posting ? 'wait' : '' }} type="submit">Post Your Job</button>
                    </div>
                </form >;
        };

        return (
            <div className="PostJob" >
                <div className={paymentStatusClasses.join(' ')} >
                    {paymentStatus}
                </div>
                {rendered}
                <PaypalButton
                    title={this.state.title.value}
                    productPrice={this.state.jobPrice}
                    approved={(order) => this.approvedPaymentHandler(order)}
                    loading={this.props.loading}
                />
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
        onPostJob: (formData, token) => dispatch(actions.postJob(formData, token))
    }
}


export default connect(mapStateToProps, mapDispatchToProps)(PostJob);