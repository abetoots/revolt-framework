import React, { Component } from 'react';
import './Auth.css';


//Components
import Spinner from '../../components/UI/Spinner/Spinner';
import { Redirect } from 'react-router';
import Input from '../../components/UI/Input/Input';
import Button from '../../components/UI/Button/Button';
import Aux from '../../hoc/Auxiliary';

//Redux
import * as actions from '../../store/actions/index';
import { connect } from 'react-redux';
//util
import { checkInputValidity, updateObject } from '../../utility/utility';
export class Auth extends Component {
    state = {
        controls: {
            email: {
                elementType: 'input',
                elementConfig: {
                    type: 'email',
                    placeholder: 'email@domain.com'
                },
                value: '',
                validation: {
                    required: true,
                    isEmail: true
                },
                valid: false,
                touched: false
            },
            password: {
                elementType: 'input',
                elementConfig: {
                    type: 'password',
                    placeholder: 'Your Password'
                },
                value: '',
                validation: {
                    required: true,
                    minLength: 6
                },
                valid: false,
                touched: false
            }
        }
    }

    inputChangedHandler = (event, controlName) => {
        const updatedControls = updateObject(this.state.controls, {
            [controlName]: updateObject(this.state.controls[controlName], {
                value: event.target.value,
                valid: checkInputValidity(event.target.value, this.state.controls[controlName].validation),
                touched: true
            })
        });

        this.setState({ controls: updatedControls });
    }

    submitHandler = (event) => {
        event.preventDefault();
        this.props.authenticate(this.state.controls.email.value, this.state.controls.password.value);
    }

    render() {
        let content = <Spinner />;

        let redirectIfAuth = null;
        if (this.props.valid && this.props.loadedProfile && !this.props.userIsNew) {
            redirectIfAuth =
                <Redirect to="/dashboard/overview" />;
        }

        //If we're in development, show a form to authenticate our user
        if (this.props.isDev) {
            const formElementsArray = [];
            for (let key in this.state.controls) {
                formElementsArray.push({
                    id: key,
                    config: this.state.controls[key]
                });
            };

            content =
                <Aux>
                    <span role="img" aria-label="warning">️⚠️ {this.props.isDev}</span>
                    {
                        formElementsArray.map(formElement => (
                            <Input
                                key={formElement.id}
                                elementType={formElement.config.elementType}
                                elementConfig={formElement.config.elementConfig}
                                value={formElement.config.value}
                                invalid={!formElement.config.valid}
                                shouldValidate={formElement.config.validation}
                                touched={formElement.config.touched}
                                changed={(event) => this.inputChangedHandler(event, formElement.id)} />
                        ))
                    }
                    < Button btnType="blue" > LOG IN</Button>
                </Aux>;
        } else if (this.props.error) {
            //Production error
            content = <span role="img" aria-label="warning">️⚠️ {this.props.error}</span>;
        } else if (this.props.userIsNew) {
            content = <span role="img" aria-label="warning">️⚠️ Please update your profile first</span>;
        }

        return (
            <div className="Auth">
                {redirectIfAuth}
                {content}
            </div>
        );

    }
};


const mapStateToProps = state => {
    return {
        valid: state.auth.valid,
        isDev: state.auth.dev,
        loadedProfile: state.profile.loaded,
        userIsNew: state.profile.isNew
    }
}

const mapDispatchToProps = dispatch => {
    return {
        fetchProfileOnMount: (userName) => dispatch(actions.fetchProfile(userName)),
        authenticate: (email, password) => dispatch(actions.authenticateUser(email, password))
    }
}

export default connect(mapStateToProps, mapDispatchToProps)(Auth);