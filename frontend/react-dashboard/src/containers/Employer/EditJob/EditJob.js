import React, { Component } from 'react';
import { connect } from 'react-redux';
import asyncComponent from '../../../hoc/asyncComponent/asyncComponent';

import FroalaEditor from 'react-froala-wysiwyg';
import Input from '../../../components/UI/Input/Input';
import Spinner from '../../../components/UI/Spinner/Spinner';

import { manageJobForm } from '../../../utility/jobForm';
import { checkValidity } from '../../../utility/utility'
import * as actions from '../../../store/actions/index';

/**
 * Lazy loads the editor, dont really get why this fixes a critical error
 * that gets triggered by the editor once I added some async actions to this component
 * 
 * Following link from an opened issue mentions making sure froala scripts are not included twice
 * @link https://github.com/froala/angular-froala-wysiwyg/issues/246
 */
const AsyncEditor = asyncComponent(() => {
    return import('../../../components/UI/Editor/Editor');
})
class EditJob extends Component {

    state = {
        manageJobForm: manageJobForm,
        formIsValid: false,
        model: '<p>Sample</p>'
    }

    handleModelChange = (model) => {
        this.setState({ model: model });
    }

    inputChangedHandler = (event, groupIdentifier, identifier) => {
        //clone the order form
        const updatedJobForm = {
            ...this.state.manageJobForm
        };

        //deeply clone each key's object
        const updatedInfoElement = {
            ...updatedJobForm[groupIdentifier].info[identifier]
        };

        //update the key object's value
        updatedInfoElement.value = event.target.value;
        //validate our input values
        updatedInfoElement.valid = checkValidity(updatedInfoElement.value, updatedInfoElement.validation)
        //since the user has touched the input, set touched to true , used for adding classnames in input.js
        updatedInfoElement.touched = true;
        //update our updateProfileForm clone's key objects
        updatedJobForm[groupIdentifier].info[identifier] = updatedInfoElement;

        let formIsValid = true;
        for (let i in updatedJobForm.info) {
            formIsValid = updatedJobForm[i].valid && formIsValid;
        }
        this.setState({ manageJobForm: updatedJobForm, formIsValid: formIsValid });
    }

    componentDidMount() {
        // console.log(this.props);
        this.props.fetchOnMount(this.props.match.params.jobID);
        console.log(this.state.manageJobForm);
    }
    componentWillUnmount() {
        console.log('editjob will unmount');
    }
    render() {
        /**
         * Setup an array data structure to so we can dynamically render JSX through array.map()
         * @returns Data structure [ [{groupID:'',heading:''}], [{object}] ]
         * @summary Each element in the array contains TWO arrays containing objects like so: [ [{object}],[{object}] ]
         */
        const formElementsArray = [];
        for (let groupKey in this.state.manageJobForm) {
            // temporary array to be added at the end of the loop
            let x = [];
            // temporary array for 2nd loop to be added to our first arr 'x'
            let y = [];
            //Push an object with groupId and heading
            //groupID is used as an identifier for inputChangedHandler
            x.push({
                groupID: groupKey,
                heading: this.state.manageJobForm[groupKey].heading
            });
            //Push each info item as an object
            for (let inputKey in this.state.manageJobForm[groupKey].info) {
                y.push({
                    id: inputKey,
                    config: this.state.manageJobForm[groupKey].info[inputKey]
                });
            }
            //push the y array containing all our 'info' items (e.g. name, website, headline)
            x.push(y);
            //Push the temporary array as one element to the final array
            formElementsArray.push(x);
        }

        /**
         * Switch between a form or <Spinner/> if state.loading : true
         * @uses formElementsArray
         */
        let rendered = this.props.error ? <p>{this.props.error.message}</p> : <Spinner />;
        if (!this.props.loading) {
            rendered = (
                <form onSubmit={this.submitHandler}>

                    {
                        /**
                         * Maps the first array element
                         */
                        formElementsArray.map((arrayElement, index) => {
                            let objectsInArr = arrayElement[1];
                            return (
                                <section key={index}>
                                    <h2 className="Profile__heading">{arrayElement[0].heading}</h2>
                                    {
                                        /**
                                         * Maps the objects inside the second array element into Input components or an Editor Component
                                         */
                                        objectsInArr.map(objEl => {
                                            let renderedComponent =
                                                <Input
                                                    key={objEl.id}
                                                    label={objEl.config.label}
                                                    elementType={objEl.config.elementType}
                                                    elementConfig={objEl.config.elementConfig}
                                                    value={objEl.config.value}
                                                    invalid={!objEl.config.valid}
                                                    shouldValidate={objEl.config.validation}
                                                    touched={objEl.config.touched}
                                                    changed={(event) => this.inputChangedHandler(event, arrayElement[0].groupID, objEl.id)}
                                                />;
                                            if (objEl.config.elementType === 'editor') {
                                                renderedComponent =
                                                    <div key={objEl.id}>
                                                        <label className="Label">{objEl.config.label}</label>
                                                        <FroalaEditor
                                                            model={this.state.model}
                                                            changed={this.handleModelChange}
                                                            config={objEl.config.elementConfig}
                                                        />
                                                    </div>
                                            }
                                            return (renderedComponent);
                                        }) // end map
                                    }

                                </section>
                            );
                        })
                    }
                    <button type="submit">Submit</button>
                </form>
            );
        }

        return (
            <div className="EditJob">
                {rendered}
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        fetchedJob: state.job.fetchedJob,
        userId: state.auth.userId,
        jobsAlreadyLoaded: state.jobs.jobs !== null,
        error: state.job.error,
        loading: state.job.loading
    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchOnMount: (jobID) => dispatch(actions.fetchJob(jobID))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(EditJob);