import React, { Component } from 'react';

import Input from '../../../components/UI/Input/Input';
import Editor from '../../../components/UI/Editor/Editor';
import Spinner from '../../../components/UI/Spinner/Spinner';

import { checkValidity } from '../../../utility/utility';
import { jobForm } from '../../../utility/jobForm';
class PostJob extends Component {
    state = {
        jobForm: jobForm,
        loading: false,
        formIsValid: false,
        //Froala model (think of it as value)
        model: ''
    }

    handleModelChange = (model) => {
        this.setState({ model: model });
    }

    submitHandler = (event) => {
        event.preventDefault();
    }

    inputChangedHandler = (event, groupIdentifier, identifier) => {
        //clone the order form
        const updatedJobForm = {
            ...this.state.jobForm
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
        this.setState({ jobForm: updatedJobForm, formIsValid: formIsValid });
    }
    render() {
        /**
         * Setup an array data structure to so we can dynamically render JSX through array.map()
         * @returns Data structure [ [{groupID:'',heading:''}], [{object}] ]
         * @summary Each element in the array contains TWO arrays containing objects like so: [ [{object}],[{object}] ]
         */
        const formElementsArray = [];
        for (let groupKey in this.state.jobForm) {
            // temporary array to be added at the end of the loop
            let x = [];
            // temporary array for 2nd loop to be added to our first arr 'x'
            let y = [];
            //Push an object with groupId and heading
            //groupID is used as an identifier for inputChangedHandler
            x.push({
                groupID: groupKey,
                heading: this.state.jobForm[groupKey].heading
            });
            //Push each info item as an object
            for (let inputKey in this.state.jobForm[groupKey].info) {
                y.push({
                    id: inputKey,
                    config: this.state.jobForm[groupKey].info[inputKey]
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
        let form = (
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
                                                    <Editor
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
        if (this.state.loading) {
            form = <Spinner />
        }

        return (
            <div className="PostJob">
                {form}
            </div>
        )
    }
};

export default PostJob;