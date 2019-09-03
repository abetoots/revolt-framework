import React, { Component } from 'react';
import './Profile.scss';

import clone from 'lodash.clonedeep';

import { connect } from 'react-redux';
import * as actions from '../../store/actions/index';

import Spinner from '../../components/UI/Spinner/Spinner';
import Input from '../../components/UI/Input/Input';

import { checkValidity } from '../../utility/utility';
import { profileForm } from '../../utility/profileForm';
// import axios from '../../axios-wp-dev-only';


class Profile extends Component {
    state = {
        profileForm: profileForm,
        formIsValid: false
    };

    /**
     * By default, our app tries to load the settings already on login
     * When this component is first mounted, check if the settings have already been loaded then 
     * set the values
     * 
     * Fallback: If we mount this component but the settings haven't been loaded yet, then there are
     * two scenarios:
     * 1)More likely, we are still loading. Therefore we listen to changes of this.props.loaded on componentDidUpdate
     * then set the values  from there
     * 2)An error has occured, which is handled in the render method
     */
    componentDidMount() {
        if (this.props.loaded) {
            // this.props.fetchOnMount(this.props.userName);
            const clonedForm = clone(this.state.profileForm);
            for (const i in profileForm) {
                for (const infoKey in profileForm[i].info) {
                    // debugger;
                    clonedForm[i].info[infoKey].value = this.props.profileInfo[infoKey];
                }
            }
            this.setState({ profileForm: clonedForm });
        }
    }

    componentDidUpdate(prevProps, prevState) {
        if (prevProps !== this.props && this.props.loaded) {
            const clonedForm = clone(this.state.profileForm);
            for (const i in profileForm) {
                for (const infoKey in profileForm[i].info) {
                    // debugger;
                    clonedForm[i].info[infoKey].value = this.props.profileInfo[infoKey];
                }
            }
            this.setState({ profileForm: clonedForm });
        }
    }


    submitHandler = (event) => {
        event.preventDefault();
        for (const groupKey in this.state.profileForm) {
            console.log(this.state.profileForm[groupKey]);
        }
        console.log('ye');
    }
    //!Testing
    testClick = () => {
        const formData = {};
        for (const groupKey in this.state.profileForm) {
            // console.log(this.state.profileForm[groupKey]);
            for (let fieldName in this.state.profileForm[groupKey].info) {
                formData[fieldName] = this.state.profileForm[groupKey].info[fieldName].value;
            }
        }
        console.log(formData);
    }

    inputChangedHandler = (event, groupIdentifier, identifier) => {
        //clone the order form
        const updatedProfileForm = {
            ...this.state.profileForm
        };

        //deeply clone each key's object
        const updatedInfoElement = {
            ...updatedProfileForm[groupIdentifier].info[identifier]
        };

        //update the key object's value
        updatedInfoElement.value = event.target.value;
        //validate our input values
        updatedInfoElement.valid = checkValidity(updatedInfoElement.value, updatedInfoElement.validation)
        /**
         * To prevent the triggering the invalid class when an input is optional BUT 
         * still check validation when the user did input something, we only overrun the 
         * validity check above when the input is OPTIONAL and the user chose not to enter anything (value is null)
         */
        if (!updatedInfoElement.validation.required && event.target.value === '') {
            updatedInfoElement.valid = true;
        }
        //since the user has touched the input, set touched to true , used for adding classnames in input.js
        updatedInfoElement.touched = true;
        //update our profileForm clone's key objects
        updatedProfileForm[groupIdentifier].info[identifier] = updatedInfoElement;

        let formIsValid = true;
        for (let i in updatedProfileForm.info) {
            formIsValid = updatedProfileForm[i].valid && formIsValid;
        }
        this.setState({ profileForm: updatedProfileForm, formIsValid: formIsValid });
    }


    render() {
        // console.log(this.props);
        /**
         * Setup an array data structure to so we can dynamically render JSX through array.map()
         * @returns Data structure [ [{groupID:'',heading:''}], [{object}] ]
         * @summary Each element in the array contains TWO arrays containing objects like so: [ [{object}],[{object}] ]
         */
        const formElementsArray = [];
        for (let groupKey in this.state.profileForm) {
            // temporary array to be added at the end of the loop
            let x = [];
            // temporary array for 2nd loop to be added to our first arr 'x'
            let y = [];
            //Push an object with groupId and heading
            //groupID is used as an identifier for inputChangedHandler
            x.push({
                groupID: groupKey,
                heading: this.state.profileForm[groupKey].heading
            });
            //Push each info item as an object
            for (let inputKey in this.state.profileForm[groupKey].info) {
                y.push({
                    id: inputKey,
                    config: this.state.profileForm[groupKey].info[inputKey]
                });
            }
            //push the i array containing all our 'info' items (e.g. name, website, headline)
            x.push(y);
            //Push the temporary array as one element to the final array
            formElementsArray.push(x);
        }

        /**
         * Switch between a form or <Spinner/> if state.loading : true
         * @uses formElementsArray 
         */
        let form = (
            <form onSubmit={this.submitHandler} className="Profile__form">
                {
                    /**
                     * Maps the first array element
                     */
                    formElementsArray.map((arrayElement, index) => {
                        // Initialize the 2nd array eleemnt to be mapped below
                        let objectsInArr = arrayElement[1];
                        return (
                            <section key={index} className="Profile__section">
                                <h2 className="Profile__heading">{arrayElement[0].heading}</h2>
                                {
                                    /**
                                     * Maps the objects inside the second array element into Input components
                                     */
                                    objectsInArr.map(objEl => (
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
                                        />))
                                }
                            </section>
                        );
                    })
                }

            </form>
        );
        if (this.props.loading) {
            console.log('loading');
            form = <Spinner />
        }
        if (this.props.error) {
            form = <p>Failed to load your settings. Try reloading the page</p>;
        }

        return (
            <div className="Profile -settings">
                {form}
                <button onClick={this.testClick}>Submit</button>
            </div>
        );
    }
};

const mapStateToProps = state => {
    return {
        userName: state.auth.userName,
        loading: state.profile.loading,
        loaded: state.profile.loaded,
        error: state.profile.error,
        profileInfo: state.profile.profileInfo
    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchOnMount: (userName) => dispatch(actions.fetchProfile(userName)),
        // inputChange: (eventValue, identifier) => dispatch(actions.profileInputHandler(eventValue, identifier))
    }
}
export default connect(mapStateToProps, mapDispatchToProps)(Profile);